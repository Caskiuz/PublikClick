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
        $transaction = Transaction::findOrFail($transactionId);
        
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Transacción no válida']);
        }
        
        DB::beginTransaction();
        
        try {
            $transaction->status = 'completed';
            $transaction->save();
            
            // Actualizar total_withdrawn en wallet
            $wallet = $transaction->user->withdrawalWallet;
            $wallet->total_withdrawn += abs($transaction->amount);
            $wallet->save();
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Retiro aprobado exitosamente']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al aprobar retiro']);
        }
    }
    
    public function rejectWithdrawal(Request $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Transacción no válida']);
        }
        
        DB::beginTransaction();
        
        try {
            // Devolver fondos al usuario
            $wallet = $transaction->user->withdrawalWallet;
            $wallet->balance += abs($transaction->amount);
            $wallet->save();
            
            $transaction->status = 'rejected';
            $transaction->save();
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Retiro rechazado y fondos devueltos']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al rechazar retiro']);
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