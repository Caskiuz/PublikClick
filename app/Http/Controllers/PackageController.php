<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\MegaAd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)
                          ->orderBy('price_usd', 'asc')
                          ->get();
        $user = Auth::user();
        $user->load(['currentPackage', 'currentRank']);
        
        return view('packages.index', compact('packages', 'user'));
    }
    
    public function show(Package $package)
    {
        return view('packages.show', compact('package'));
    }
    
    public function purchase(Request $request, Package $package)
    {
        try {
            $user = Auth::user();
            
            if (!$package->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este paquete no está disponible'
                ], 400);
            }
            
            // Aquí integrarías el pago con Nequi
            // Por ahora solo activa el paquete
            $user->current_package_id = $package->id;
            $user->package_purchased_at = now();
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => "¡Paquete {$package->name} activado! Procesa el pago de \${$package->price_usd} por Nequi.",
                'redirect' => route('dashboard')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getAvailable()
    {
        $packages = Package::where('is_active', true)
            ->orderBy('price_usd', 'asc')
            ->get()
            ->map(function($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price_usd' => $package->price_usd,
                    'price_cop' => $package->price_cop,
                    'banner_views' => $package->banner_views,
                    'post_views' => $package->post_views,
                    'ptc_views' => $package->ptc_views,
                    'main_ad_value' => $package->main_ad_value,
                    'mini_ad_value' => $package->mini_ad_value,
                    'mini_ads_count' => $package->mini_ads_count,
                    'monthly_potential' => $package->getMonthlyEarningsPotential()
                ];
            });
            
        return response()->json([
            'packages' => $packages
        ]);
    }
    
    public function getUserPackage()
    {
        $user = Auth::user();
        $user->load(['currentPackage', 'currentRank', 'withdrawalWallet', 'donationWallet']);
        
        $currentPackage = null;
        if ($user->currentPackage) {
            $currentPackage = [
                'id' => $user->currentPackage->id,
                'name' => $user->currentPackage->name,
                'purchased_at' => $user->package_purchased_at,
                'main_ad_earnings' => $user->calculateMainAdEarnings(),
                'mini_ad_earnings' => $user->calculateMiniAdEarnings()
            ];
        }
        
        return response()->json([
            'current_package' => $currentPackage,
            'current_rank' => $user->currentRank ? [
                'name' => $user->currentRank->name,
                'mega_ads_monthly' => $user->currentRank->mega_ads_monthly,
                'mini_ads_daily' => $user->currentRank->mini_ads_daily,
                'referral_commission' => $user->currentRank->referral_commission
            ] : null,
            'active_referrals' => $user->getActiveReferralsCount(),
            'wallet_balance' => $user->getAvailableBalance(),
            'total_earnings' => $user->getTotalEarnings(),
            'daily_clicks_remaining' => $user->canClickMainAds() ? (5 - $user->getTodayClicksCount()) : 0
        ]);
    }
}