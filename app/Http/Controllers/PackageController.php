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
        $request->validate([
            'currency' => 'required|in:COP,USD',
            'payment_method' => 'required|string',
            'nequi_phone' => 'required_if:payment_method,nequi|string|max:20'
        ]);
        
        $user = Auth::user();
        
        // Validar que el paquete esté activo
        if (!$package->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Este paquete no está disponible'
            ]);
        }
        
        DB::beginTransaction();
        
        try {
            $amount = $request->currency === 'USD' ? $package->price_usd : $package->price_cop;
            
            // Activar paquete para el usuario
            $user->current_package_id = $package->id;
            $user->package_purchased_at = now();
            
            // Actualizar teléfono Nequi si se proporciona
            if ($request->nequi_phone) {
                $user->nequi_phone = $request->nequi_phone;
            }
            
            $user->save();
            
            // Actualizar rango del usuario
            $user->updateRank();
            
            // Crear mega ads para el mes actual
            MegaAd::getOrCreateForUser($user->id);
            
            // Registrar transacción
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'package_purchase',
                'amount' => -$amount,
                'description' => "Compra de paquete: {$package->name}",
                'status' => 'completed'
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "¡Paquete {$package->name} activado exitosamente! Ya puedes comenzar a hacer clicks.",
                'package' => $package,
                'user_rank' => $user->currentRank->name ?? 'Jade',
                'redirect' => route('dashboard')
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la compra: ' . $e->getMessage()
            ]);
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