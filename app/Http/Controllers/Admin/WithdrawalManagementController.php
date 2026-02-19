<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class WithdrawalManagementController extends Controller
{
    public function index()
    {
        $withdrawals = Transaction::where('type', 'withdrawal')
            ->with('user')
            ->latest()
            ->paginate(20);
        
        $stats = [
            'pending' => Transaction::where('type', 'withdrawal')->where('status', 'pending')->count(),
            'approved' => Transaction::where('type', 'withdrawal')->where('status', 'completed')->count(),
            'rejected' => Transaction::where('type', 'withdrawal')->where('status', 'rejected')->count(),
            'total_amount' => Transaction::where('type', 'withdrawal')->where('status', 'completed')->sum('amount')
        ];
        
        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Solo se pueden aprobar retiros pendientes');
        }
        
        $transaction->status = 'completed';
        $transaction->processed_at = now();
        $transaction->save();
        
        return back()->with('success', 'Retiro aprobado exitosamente');
    }

    public function reject($id, Request $request)
    {
        $transaction = Transaction::findOrFail($id);
        
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Solo se pueden rechazar retiros pendientes');
        }
        
        // Devolver fondos al usuario
        $user = $transaction->user;
        $user->wallet_balance += $transaction->amount;
        $user->save();
        
        $transaction->status = 'rejected';
        $transaction->processed_at = now();
        $transaction->notes = $request->reason ?? 'Rechazado por administrador';
        $transaction->save();
        
        return back()->with('success', 'Retiro rechazado y fondos devueltos');
    }
}
