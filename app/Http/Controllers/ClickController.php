<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ad;
use App\Models\UserAdClick;
use App\Models\MegaAd;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class ClickController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar anuncios disponibles para el usuario
     */
    public function index()
    {
        $user = Auth::user();
        
        // Cargar relaciones necesarias
        $user->load(['currentPackage', 'currentRank', 'withdrawalWallet', 'donationWallet']);
        
        // Obtener estadísticas de clicks de hoy
        $todayMainClicks = $user->adClicks()->whereDate('clicked_at', today())
                               ->where('ad_type', 'main')->count();
        $todayMiniClicks = $user->adClicks()->whereDate('clicked_at', today())
                               ->where('ad_type', 'mini')->count();
        
        // Obtener mega ad del mes actual
        $megaAd = $user->getCurrentMegaAd();
        
        // Obtener anuncios disponibles
        $availableAds = Ad::where('is_active', true)
                         ->inRandomOrder()
                         ->limit(5)
                         ->get();
        
        return view('dashboard.clicks', compact(
            'user', 
            'todayMainClicks', 
            'todayMiniClicks', 
            'megaAd', 
            'availableAds'
        ));
    }

    /**
     * Procesar click en anuncio principal
     */
    public function clickMainAd(Request $request, $adId)
    {
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            $user->load(['currentPackage']);
            
            $todayClicks = $user->adClicks()->whereDate('clicked_at', today())->where('ad_type', 'main')->count();
            
            if ($todayClicks >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya completaste tus 5 clicks diarios'
                ], 400);
            }
            
            $earnings = $user->calculateMainAdEarnings();
            
            UserAdClick::create([
                'user_id' => $user->id,
                'ad_id' => $adId,
                'ad_type' => 'main',
                'clicked_at' => now(),
                'earnings' => $earnings,
                'ip_address' => $request->ip()
            ]);
            
            $user->wallet_balance += $earnings;
            $user->save();
            
            DB::commit();
            
            $remaining = 5 - ($todayClicks + 1);
            
            return response()->json([
                'success' => true,
                'message' => "¡Ganaste $$earnings!",
                'earnings' => number_format($earnings, 2),
                'remaining_clicks' => $remaining,
                'new_balance' => number_format($user->wallet_balance, 2)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesar click en mini-anuncio
     */
    public function clickMiniAd(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            $user->load(['currentRank']);
            
            $todayMiniClicks = $user->adClicks()->whereDate('clicked_at', today())->where('ad_type', 'mini')->count();
            $maxMiniClicks = $user->currentRank->mini_ads_daily ?? 1;
            
            if ($todayMiniClicks >= $maxMiniClicks) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes mini-anuncios disponibles hoy'
                ], 400);
            }
            
            $earnings = $user->calculateMiniAdEarnings();
            
            UserAdClick::create([
                'user_id' => $user->id,
                'ad_id' => null,
                'ad_type' => 'mini',
                'clicked_at' => now(),
                'earnings' => $earnings,
                'ip_address' => $request->ip()
            ]);
            
            $user->wallet_balance += $earnings;
            $user->save();
            
            DB::commit();
            
            $remaining = $maxMiniClicks - ($todayMiniClicks + 1);
            
            return response()->json([
                'success' => true,
                'message' => "¡Ganaste $$earnings!",
                'earnings' => number_format($earnings, 2),
                'remaining_mini_clicks' => $remaining,
                'new_balance' => number_format($user->wallet_balance, 2)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesar click en mega-anuncio
     */
    public function clickMegaAd(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            $megaAd = $user->getCurrentMegaAd();
            
            if (!$megaAd || $megaAd->clicks_remaining <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes mega-anuncios disponibles este mes'
                ], 400);
            }
            
            $earnings = 2000;
            
            UserAdClick::create([
                'user_id' => $user->id,
                'ad_id' => null,
                'ad_type' => 'mega',
                'clicked_at' => now(),
                'earnings' => $earnings,
                'ip_address' => $request->ip()
            ]);
            
            $megaAd->clicks_used++;
            $megaAd->clicks_remaining--;
            $megaAd->save();
            
            $user->wallet_balance += $earnings;
            $user->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "¡Ganaste $$earnings!",
                'earnings' => number_format($earnings, 2),
                'remaining_mega_clicks' => $megaAd->clicks_remaining,
                'new_balance' => number_format($user->wallet_balance, 2)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de clicks del usuario
     */
    public function getClickStats()
    {
        $user = Auth::user();
        $user->load(['currentPackage', 'currentRank']);
        
        $todayMainClicks = $user->adClicks()->whereDate('clicked_at', today())
                               ->where('ad_type', 'main')->count();
        $todayMiniClicks = $user->adClicks()->whereDate('clicked_at', today())
                               ->where('ad_type', 'mini')->count();
        
        $megaAd = $user->getCurrentMegaAd();
        
        return response()->json([
            'main_clicks' => [
                'used' => $todayMainClicks,
                'available' => 5 - $todayMainClicks,
                'earnings_per_click' => $user->calculateMainAdEarnings()
            ],
            'mini_clicks' => [
                'used' => $todayMiniClicks,
                'available' => ($user->currentRank ? $user->currentRank->mini_ads_daily : 0) - $todayMiniClicks,
                'earnings_per_click' => $user->calculateMiniAdEarnings()
            ],
            'mega_clicks' => [
                'used' => $megaAd ? $megaAd->clicks_used : 0,
                'available' => $megaAd ? $megaAd->clicks_remaining : 0,
                'earnings_per_click' => 2000
            ],
            'wallet_balance' => $user->getAvailableBalance(),
            'total_earnings' => $user->getTotalEarnings()
        ]);
    }
}