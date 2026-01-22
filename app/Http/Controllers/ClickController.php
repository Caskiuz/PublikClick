<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ad;
use App\Models\UserAdClick;
use App\Models\MegaAd;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            
            // Validaciones anti-fraude
            $validation = UserAdClick::validateClick(
                $user->id, 
                $request->ip(), 
                $request->userAgent()
            );
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['reason']
                ], 400);
            }
            
            // Verificar si puede hacer clicks
            if (!$user->canClickMainAds()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya has completado tus 5 clicks diarios'
                ], 400);
            }
            
            // Verificar que el anuncio existe
            $ad = Ad::findOrFail($adId);
            if (!$ad->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anuncio no disponible'
                ], 400);
            }
            
            // Procesar click
            $click = $user->clickMainAd($adId);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Click procesado exitosamente',
                'earnings' => $click->earnings,
                'remaining_clicks' => 5 - ($user->getTodayClicksCount()),
                'wallet_balance' => $user->getAvailableBalance()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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
            
            // Validaciones anti-fraude
            $validation = UserAdClick::validateClick(
                $user->id, 
                $request->ip(), 
                $request->userAgent()
            );
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['reason']
                ], 400);
            }
            
            // Verificar si puede hacer clicks en mini-anuncios
            if (!$user->canClickMiniAds()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes mini-anuncios disponibles hoy'
                ], 400);
            }
            
            // Procesar click
            $click = $user->clickMiniAd();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Mini-anuncio clickeado exitosamente',
                'earnings' => $click->earnings,
                'remaining_mini_clicks' => $user->currentRank->mini_ads_daily - $user->getTodayMiniAdsClicks(),
                'wallet_balance' => $user->getAvailableBalance()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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
            
            // Validaciones anti-fraude
            $validation = UserAdClick::validateClick(
                $user->id, 
                $request->ip(), 
                $request->userAgent()
            );
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['reason']
                ], 400);
            }
            
            // Obtener mega ad del usuario
            $megaAd = $user->getCurrentMegaAd();
            
            if (!$megaAd || !$megaAd->canClick()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes mega-anuncios disponibles este mes'
                ], 400);
            }
            
            // Procesar click
            $click = $megaAd->recordClick();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => '¡Mega-anuncio clickeado! Ganaste $2,000',
                'earnings' => $click->earnings,
                'remaining_mega_clicks' => $megaAd->clicks_remaining,
                'wallet_balance' => $user->getAvailableBalance()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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