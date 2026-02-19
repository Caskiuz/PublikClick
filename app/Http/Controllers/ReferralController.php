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
        $tree = $this->buildReferralTree($user);
        
        $level1 = $user->referrals()->count();
        $level2 = $user->referrals()->with('referrals')->get()->sum(fn($r) => $r->referrals->count());
        $level3 = $user->referrals()->with('referrals.referrals')->get()->sum(fn($r) => 
            $r->referrals->sum(fn($r2) => $r2->referrals->count())
        );
        
        $stats = [
            'total_referrals' => $level1 + $level2 + $level3,
            'level_1' => $level1,
            'level_2' => $level2,
            'level_3' => $level3,
            'total_commissions' => $user->total_referral_earnings ?? 0,
            'active_referrals' => $user->active_referrals_count ?? 0
        ];
        
        return view('referrals.index', compact('tree', 'stats', 'user'));
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
        
        $referrals = $user->referrals()->with('currentPackage', 'currentRank')->get();
        $tree = [];
        
        foreach ($referrals as $ref) {
            $node = [
                'id' => $ref->id,
                'name' => $ref->name,
                'email' => substr($ref->email, 0, 3) . '***',
                'package' => $ref->currentPackage ? '$' . $ref->currentPackage->price_usd : 'Sin paquete',
                'rank' => $ref->currentRank ? $ref->currentRank->name : 'Sin rango',
                'level' => $level,
                'is_active' => $ref->is_active && $ref->current_package_id,
                'joined_at' => $ref->created_at->format('d/m/Y'),
                'children' => $this->buildReferralTree($ref, $level + 1, $maxLevel)
            ];
            
            $tree[] = $node;
        }
        
        return $tree;
    }
}
