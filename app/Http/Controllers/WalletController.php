<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['wallets', 'currentRank', 'activePackage']);
        
        $withdrawalWallet = $user->wallets->where('type', Wallet::TYPE_WITHDRAWAL)->first();
        $donationWallet = $user->wallets->where('type', Wallet::TYPE_DONATION)->first();
        
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $canWithdraw = Withdrawal::canUserWithdraw($user->id);
        $minimumWithdrawal = Withdrawal::getMinimumWithdrawal($user->currentRank->name ?? 'Jade');

        return view('billetera', compact('withdrawalWallet', 'donationWallet', 'recentTransactions', 'canWithdraw', 'minimumWithdrawal'));
    }

    public function requestWithdrawal(Request $request)
    {
        $user = Auth::user()->load(['wallets', 'currentRank']);
        
        $canWithdraw = Withdrawal::canUserWithdraw($user->id);
        if (!$canWithdraw['can']) {
            return back()->with('error', $canWithdraw['reason']);
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:bancolombia,nequi,daviplata,efecty,western_union,paypal,bank_transfer',
            'account_holder' => 'required|string',
            'account_number' => 'required|string',
            'document_number' => 'required|string'
        ]);

        $withdrawalWallet = $user->wallets->where('type', Wallet::TYPE_WITHDRAWAL)->first();
        $minimumWithdrawal = Withdrawal::getMinimumWithdrawal($user->currentRank->name ?? 'Jade');

        if ($request->amount < $minimumWithdrawal['cop']) {
            return back()->with('error', "El monto mínimo de retiro para tu categoría es $" . number_format($minimumWithdrawal['cop'], 0, ',', '.') . " COP");
        }

        if ($withdrawalWallet->balance < $request->amount) {
            return back()->with('error', 'Saldo insuficiente');
        }

        try {
            $withdrawalWallet->withdrawFunds($request->amount, "Solicitud de retiro vía {$request->payment_method}");
            
            $transaction = Transaction::where('user_id', $user->id)
                ->where('type', 'debit')
                ->latest()
                ->first();

            $transaction->update([
                'payment_method' => $request->payment_method,
                'payment_details' => [
                    'account_holder' => $request->account_holder,
                    'account_number' => $request->account_number,
                    'document_number' => $request->document_number
                ]
            ]);

            return back()->with('success', '¡Solicitud de retiro enviada! Será procesada en 24-48 horas.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function historialRetiros()
    {
        $user = Auth::user();
        
        $withdrawals = Transaction::where('user_id', $user->id)
            ->where('type', 'debit')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('historial-retiros', compact('withdrawals'));
    }
}
