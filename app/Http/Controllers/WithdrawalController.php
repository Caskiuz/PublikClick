<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\PaymentMethod;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['withdrawalWallet', 'transactions' => function($query) {
            $query->where('type', 'withdrawal')->orderBy('created_at', 'desc');
        }]);
        
        $paymentMethods = PaymentMethod::getActive();
        
        return view('billetera', compact('user', 'paymentMethods'));
    }
    
    public function request(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:10000',
            'payment_method' => 'required|exists:payment_methods,slug',
            'payment_account' => 'required|string',
            'password' => 'required|string'
        ]);
        
        $user = Auth::user();
        $user->load(['currentRank', 'withdrawalWallet']);
        
        $paymentMethod = PaymentMethod::getBySlug($request->payment_method);
        if (!$paymentMethod) {
            return response()->json([
                'success' => false,
                'message' => 'Método de pago no disponible'
            ], 400);
        }
        
        // Verificar contraseña
        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Contraseña incorrecta'
            ], 400);
        }
        
        // VALIDACIÓN 1: Tener paquete activo
        if (!$user->current_package_id) {
            return response()->json([
                'success' => false,
                'message' => 'Debes tener un paquete activo para retirar'
            ], 400);
        }
        
        // VALIDACIÓN 2: Tener al menos 1 invitado activo
        $activeReferrals = $user->activeReferrals()->count();
        if ($activeReferrals < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Debes tener al menos 1 invitado activo para retirar'
            ], 400);
        }
        
        // VALIDACIÓN 3: Mínimo 30 días entre retiros
        $lastWithdrawal = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($lastWithdrawal && $lastWithdrawal->created_at->diffInDays(now()) < 30) {
            $daysRemaining = 30 - $lastWithdrawal->created_at->diffInDays(now());
            return response()->json([
                'success' => false,
                'message' => "Debes esperar {$daysRemaining} días más para realizar otro retiro"
            ], 400);
        }
        
        // VALIDACIÓN 4: Monto mínimo según categoría
        $minimumWithdrawals = [
            'Jade' => 110000,
            'Perla' => 200000,
            'Zafiro' => 400000,
            'Rubí' => 1300000,
            'Esmeralda' => 1500000,
            'Diamante' => 0,
            'Diamante Azul' => 0,
            'Diamante Negro' => 0,
            'Diamante Corona' => 0,
        ];
        
        $rankName = $user->currentRank ? $user->currentRank->name : 'Jade';
        $minimumAmount = $minimumWithdrawals[$rankName] ?? 110000;
        
        if ($minimumAmount > 0 && $request->amount < $minimumAmount) {
            return response()->json([
                'success' => false,
                'message' => "El monto mínimo de retiro para tu categoría {$rankName} es $" . number_format($minimumAmount, 0)
            ], 400);
        }
        
        // Verificar balance suficiente
        $wallet = $user->withdrawalWallet;
        if (!$wallet || $wallet->balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Balance insuficiente'
            ], 400);
        }
        
        DB::beginTransaction();
        
        try {
            // Crear solicitud de retiro
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->id,
                'type' => 'withdrawal',
                'amount' => -$request->amount,
                'description' => "Retiro a {$paymentMethod->name}: {$request->payment_account}",
                'status' => 'pending',
                'payment_details' => [
                    'method' => $paymentMethod->slug,
                    'account' => $request->payment_account,
                    'requested_at' => now()->toDateTimeString()
                ],
                'metadata' => json_encode([
                    'payment_method' => $paymentMethod->slug,
                    'payment_account' => $request->payment_account,
                    'requested_at' => now()
                ])
            ]);
            
            // Descontar del balance (reservar fondos)
            $wallet->balance -= $request->amount;
            $wallet->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de retiro enviada. Será procesada en 24-48 horas.',
                'transaction_id' => $transaction->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function cancel(Request $request, $transactionId)
    {
        $user = Auth::user();
        $transaction = Transaction::where('id', $transactionId)
                                 ->where('user_id', $user->id)
                                 ->where('type', 'withdrawal')
                                 ->where('status', 'pending')
                                 ->first();
        
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transacción no encontrada o no se puede cancelar'
            ], 404);
        }
        
        DB::beginTransaction();
        
        try {
            // Devolver fondos al balance
            $wallet = $user->withdrawalWallet;
            $wallet->balance += abs($transaction->amount);
            $wallet->save();
            
            // Marcar como cancelada
            $transaction->status = 'cancelled';
            $transaction->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Retiro cancelado. Fondos devueltos a tu balance.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar el retiro'
            ], 500);
        }
    }
    
    public function history()
    {
        $user = Auth::user();
        $withdrawals = Transaction::where('user_id', $user->id)
                                 ->where('type', 'withdrawal')
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(20);
        
        return response()->json([
            'withdrawals' => $withdrawals
        ]);
    }
}