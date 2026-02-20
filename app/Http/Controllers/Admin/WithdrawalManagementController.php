<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WithdrawalManagementController extends Controller
{
    public function index()
    {
        $withdrawals = Transaction::where('type', 'withdrawal')
            ->with(['user', 'paymentMethod'])
            ->latest()
            ->paginate(20);
        
        $stats = [
            'pending' => Transaction::where('type', 'withdrawal')->where('status', 'pending')->count(),
            'approved' => Transaction::where('type', 'withdrawal')->where('status', 'completed')->count(),
            'rejected' => Transaction::where('type', 'withdrawal')->where('status', 'rejected')->count(),
            'total_amount' => Transaction::where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'awaiting_comment' => Transaction::where('type', 'withdrawal')
                ->where('status', 'completed')
                ->where('requires_comment', true)
                ->whereNull('user_comment')
                ->count()
        ];
        
        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:5120' // 5MB max
        ]);

        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Solo se pueden aprobar retiros pendientes');
        }
        
        // Subir comprobante de pago
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $transaction->payment_proof = $path;
            $transaction->payment_proof_uploaded_at = now();
        }
        
        $transaction->status = 'completed';
        $transaction->processed_at = now();
        $transaction->processed_by = auth()->id();
        $transaction->requires_comment = true; // Requerir comentario del usuario
        $transaction->save();
        
        // Bloquear sistema del usuario hasta que comente
        $user = $transaction->user;
        $user->system_locked = true;
        $user->lock_reason = 'Debes dejar un comentario sobre tu retiro para continuar usando el sistema';
        $user->save();
        
        return back()->with('success', 'Retiro aprobado. Usuario debe comentar para desbloquear sistema.');
    }

    public function reject($id, Request $request)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Solo se pueden rechazar retiros pendientes');
        }
        
        // Devolver fondos al usuario
        $user = $transaction->user;
        $wallet = $user->withdrawalWallet;
        if ($wallet) {
            $wallet->balance += abs($transaction->amount);
            $wallet->save();
        }
        
        $transaction->status = 'rejected';
        $transaction->processed_at = now();
        $transaction->processed_by = auth()->id();
        $transaction->admin_notes = $request->reason ?? 'Rechazado por administrador';
        $transaction->save();
        
        return back()->with('success', 'Retiro rechazado y fondos devueltos');
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:5120'
        ]);

        $transaction = Transaction::findOrFail($id);
        
        if ($request->hasFile('payment_proof')) {
            // Eliminar comprobante anterior si existe
            if ($transaction->payment_proof) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }
            
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $transaction->payment_proof = $path;
            $transaction->payment_proof_uploaded_at = now();
            $transaction->save();
        }
        
        return back()->with('success', 'Comprobante de pago subido exitosamente');
    }
}
