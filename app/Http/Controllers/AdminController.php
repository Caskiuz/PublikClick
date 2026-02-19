<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\UserAdClick;
use App\Models\Package;
use App\Models\Rank;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->is_admin) {
                abort(403, 'Acceso denegado');
            }
            return $next($request);
        });
    }
    
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('current_package_id')->count(),
            'total_clicks_today' => UserAdClick::whereDate('clicked_at', today())->count(),
            'pending_withdrawals' => Transaction::where('type', 'withdrawal')->where('status', 'pending')->count(),
            'total_earnings_paid' => Transaction::where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'total_revenue' => Transaction::where('type', 'package_purchase')->sum('amount')
        ];
        
        $recent_users = User::with('currentPackage')->latest()->limit(10)->get();
        $pending_withdrawals = Transaction::with('user')
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recent_users', 'pending_withdrawals'));
    }
    
    public function users()
    {
        $users = User::with(['currentPackage', 'currentRank', 'withdrawalWallet'])
                    ->paginate(20);
                    
        return view('admin.users', compact('users'));
    }
    
    public function withdrawals()
    {
        $withdrawals = Transaction::with('user')
            ->where('type', 'withdrawal')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.withdrawals', compact('withdrawals'));
    }
    
    public function approveWithdrawal(Request $request, $transactionId)
    {
        $request->validate([
            'proof_image' => 'required|image|max:5120',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $transaction = Transaction::findOrFail($transactionId);
        
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return back()->with('error', 'Transacción no válida');
        }
        
        DB::beginTransaction();
        
        try {
            // Upload comprobante
            $proofPath = $request->file('proof_image')->store('proofs', 'public');
            
            $transaction->status = 'completed';
            $transaction->proof_image = $proofPath;
            $transaction->admin_notes = $request->admin_notes;
            $transaction->processed_at = now();
            $transaction->processed_by = auth()->id();
            $transaction->requires_comment = true;
            $transaction->save();
            
            // Actualizar total_withdrawn en wallet
            $wallet = $transaction->user->withdrawalWallet;
            $wallet->total_withdrawn += abs($transaction->amount);
            $wallet->save();
            
            DB::commit();
            
            return back()->with('success', 'Retiro aprobado. Usuario debe comentar comprobante.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al aprobar retiro: ' . $e->getMessage());
        }
    }
    
    public function rejectWithdrawal(Request $request, $transactionId)
    {
        $request->validate(['admin_notes' => 'required|string|max:500']);

        $transaction = Transaction::findOrFail($transactionId);
        
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return back()->with('error', 'Transacción no válida');
        }
        
        DB::beginTransaction();
        
        try {
            // Devolver fondos al usuario
            $wallet = $transaction->user->withdrawalWallet;
            $wallet->balance += abs($transaction->amount);
            $wallet->save();
            
            $transaction->status = 'rejected';
            $transaction->admin_notes = $request->admin_notes;
            $transaction->processed_at = now();
            $transaction->processed_by = auth()->id();
            $transaction->save();
            
            DB::commit();
            
            return back()->with('success', 'Retiro rechazado y fondos devueltos');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al rechazar retiro');
        }
    }
    
    public function reports()
    {
        $daily_stats = UserAdClick::selectRaw('DATE(clicked_at) as date, COUNT(*) as clicks, SUM(earnings) as earnings')
            ->where('clicked_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
            
        $rank_distribution = User::selectRaw('ranks.name, COUNT(*) as count')
            ->leftJoin('ranks', 'users.current_rank_id', '=', 'ranks.id')
            ->groupBy('ranks.name')
            ->get();
            
        return view('admin.reports', compact('daily_stats', 'rank_distribution'));
    }
}