<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['withdrawalWallet', 'transactions' => function($query) {
            $query->where('type', 'withdrawal')->orderBy('created_at', 'desc');
        }]);
        
        return view('billetera', compact('user'));
    }
    
    public function request(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:10000',
            'nequi_phone' => 'required|string|regex:/^[0-9]{10}$/',
            'password' => 'required|string'
        ]);
        
        $user = Auth::user();
        
        // Verificar contraseña
        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Contraseña incorrecta'
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
                'type' => 'withdrawal',
                'amount' => -$request->amount,
                'description' => "Retiro a Nequi: {$request->nequi_phone}",
                'status' => 'pending',
                'metadata' => json_encode([
                    'nequi_phone' => $request->nequi_phone,
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
                'message' => 'Error al procesar la solicitud'
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