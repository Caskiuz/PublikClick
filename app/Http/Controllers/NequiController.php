<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NequiController extends Controller
{
    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'nequi_phone' => 'required|digits:10'
        ]);

        $user = Auth::user();
        
        if ($user->wallet_balance < $request->amount) {
            return back()->with('error', 'Saldo insuficiente');
        }

        // Deducir del balance
        $user->wallet_balance -= $request->amount;
        $user->nequi_phone = $request->nequi_phone;
        $user->save();

        // Crear transacción
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'status' => 'pending',
            'payment_method' => 'nequi',
            'description' => 'Retiro a Nequi ' . $request->nequi_phone
        ]);

        return back()->with('success', 'Solicitud de retiro enviada. Será procesada en 24-48 horas.');
    }

    public function savePhone(Request $request)
    {
        $request->validate(['nequi_phone' => 'required|digits:10']);
        
        $user = Auth::user();
        $user->nequi_phone = $request->nequi_phone;
        $user->save();

        return back()->with('success', 'Número Nequi guardado exitosamente');
    }
}
