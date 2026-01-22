<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Obtener árbol de referidos
        $referralTree = $user->getReferralTree();
        
        // Estadísticas de referidos
        $stats = [
            'total_referrals' => $user->referrals()->count(),
            'level_1' => $user->referrals()->count(),
            'level_2' => $user->referrals()->with('referrals')->get()->sum(function($ref) {
                return $ref->referrals->count();
            }),
            'level_3' => 0, // Calcular nivel 3
            'total_commissions' => $user->transactions()
                ->where('type', 'referral_commission')
                ->sum('amount')
        ];
        
        return view('referrals.index', compact('referralTree', 'stats', 'user'));
    }
    
    public function generateCode()
    {
        $user = Auth::user();
        
        if (!$user->referral_code) {
            $user->referral_code = $this->generateUniqueCode();
            $user->save();
        }
        
        return response()->json([
            'success' => true,
            'referral_code' => $user->referral_code,
            'referral_link' => route('register') . '?ref=' . $user->referral_code
        ]);
    }
    
    private function generateUniqueCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());
        
        return $code;
    }
    
    public function getStats()
    {
        $user = Auth::user();
        
        $directReferrals = $user->referrals()->get();
        
        $stats = [
            'direct_referrals' => $directReferrals->count(),
            'active_referrals' => $directReferrals->where('is_active', true)->count(),
            'total_commissions_today' => $user->transactions()
                ->where('type', 'referral_commission')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'total_commissions_month' => $user->transactions()
                ->where('type', 'referral_commission')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'referral_tree' => $this->buildReferralTree($user)
        ];
        
        return response()->json($stats);
    }
    
    private function buildReferralTree(User $user, $level = 1, $maxLevel = 3)
    {
        if ($level > $maxLevel) return [];
        
        $referrals = $user->referrals()->get();
        $tree = [];
        
        foreach ($referrals as $referral) {
            $node = [
                'id' => $referral->id,
                'name' => $referral->name,
                'email' => $referral->email,
                'package' => 'Sin paquete',
                'level' => $level,
                'joined_at' => $referral->created_at->format('d/m/Y'),
                'children' => $this->buildReferralTree($referral, $level + 1, $maxLevel)
            ];
            
            $tree[] = $node;
        }
        
        return $tree;
    }
}
