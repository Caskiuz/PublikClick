<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ad;
use App\Models\UserAdClick;
use App\Models\MegaAd;
use App\Models\AvailableAd;
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
            $todayClicks = $user->adClicks()->whereDate('clicked_at', today())->where('ad_type', 'main')->count();
            
            if ($todayClicks >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya completaste tus 5 clicks diarios'
                ], 400);
            }
            
            $earningsCOP = $user->calculateMainAdEarnings();
            
            // Marcar anuncio como usado
            if (!AvailableAd::markAsUsed($user->id, 'main')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes anuncios principales disponibles'
                ], 400);
            }
            
            UserAdClick::create([
                'user_id' => $user->id,
                'ad_id' => $adId,
                'ad_type' => 'main',
                'clicked_at' => now(),
                'earnings' => $earningsCOP,
                'ip_address' => $request->ip(),
                'is_valid' => true
            ]);
            
            // Distribuir ganancias: $10 a donaciones, resto a retiro
            $user->donationWallet()->first()->addFunds(10, "Click en anuncio principal");
            $user->withdrawalWallet()->first()->addFunds($earningsCOP, "Click en anuncio principal");
            
            // Pagar comisión al referidor
            $this->payReferralCommission($user, $earningsCOP);
            
            DB::commit();
            
            $remaining = 5 - ($todayClicks + 1);
            
            return response()->json([
                'success' => true,
                'message' => "¡Ganaste $" . number_format($earningsCOP, 0) . " COP!",
                'earnings' => $earningsCOP,
                'remaining_clicks' => $remaining,
                'new_balance' => $user->withdrawalWallet()->first()->balance
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
            
            $earningsCOP = $user->calculateMiniAdEarnings();
            
            // Marcar mini-anuncio como usado
            if (!AvailableAd::markAsUsed($user->id, 'mini')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes mini-anuncios disponibles'
                ], 400);
            }
            
            UserAdClick::create([
                'user_id' => $user->id,
                'ad_id' => null,
                'ad_type' => 'mini',
                'clicked_at' => now(),
                'earnings' => $earningsCOP,
                'ip_address' => $request->ip(),
                'is_valid' => true
            ]);
            
            // Todo va a cartera de retiro
            $user->withdrawalWallet()->first()->addFunds($earningsCOP, "Click en mini-anuncio");
            
            DB::commit();
            
            $remaining = $maxMiniClicks - ($todayMiniClicks + 1);
            
            return response()->json([
                'success' => true,
                'message' => "¡Ganaste $" . number_format($earningsCOP, 0) . " COP!",
                'earnings' => $earningsCOP,
                'remaining_mini_clicks' => $remaining,
                'new_balance' => $user->withdrawalWallet()->first()->balance
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
            
            $earningsCOP = 2000;
            
            // Marcar mega-anuncio como usado
            if (!AvailableAd::markAsUsed($user->id, 'mega')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes mega-anuncios disponibles'
                ], 400);
            }
            
            UserAdClick::create([
                'user_id' => $user->id,
                'ad_id' => null,
                'ad_type' => 'mega',
                'clicked_at' => now(),
                'earnings' => $earningsCOP,
                'ip_address' => $request->ip(),
                'is_valid' => true
            ]);
            
            $megaAd->clicks_used++;
            $megaAd->clicks_remaining--;
            $megaAd->save();
            
            // Todo va a cartera de retiro
            $user->withdrawalWallet()->first()->addFunds($earningsCOP, "Click en mega-anuncio");
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "¡Ganaste $" . number_format($earningsCOP, 0) . " COP!",
                'earnings' => $earningsCOP,
                'remaining_mega_clicks' => $megaAd->clicks_remaining,
                'new_balance' => $user->withdrawalWallet()->first()->balance
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
    
    public function viewAd($type, $ad)
    {
        $adModel = Ad::findOrFail($ad);
        
        $durations = [
            'main' => 90,
            'mini' => 60,
            'mega' => 120
        ];
        
        return view('view-ad', [
            'ad' => $adModel,
            'duration' => $durations[$type] ?? 90,
            'adType' => $type
        ]);
    }
    
    public function validateView(Request $request, $type, $ad)
    {
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            
            // Verificar si hay anuncios disponibles
            $available = AvailableAd::getAvailableCount($user->id, $type);
            if ($available <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes anuncios disponibles de este tipo'
                ], 400);
            }
            
            $earnings = 0;
            
            if ($type === 'main') {
                $earnings = $user->calculateMainAdEarnings();
                
                UserAdClick::create([
                    'user_id' => $user->id,
                    'ad_id' => $ad,
                    'ad_type' => 'main',
                    'clicked_at' => now(),
                    'earnings' => $earnings,
                    'ip_address' => $request->ip(),
                    'is_valid' => true
                ]);
                
                // Distribuir: $10 donaciones, resto retiro
                $user->donationWallet()->first()->addFunds(10, "Click anuncio principal");
                $user->withdrawalWallet()->first()->addFunds($earnings, "Click anuncio principal");
                
                AvailableAd::markAsUsed($user->id, 'main');
                
                // Pagar comisión al referidor
                $this->payReferralCommission($user, $earnings);
                
            } elseif ($type === 'mini') {
                $earnings = $user->calculateMiniAdEarnings();
                
                UserAdClick::create([
                    'user_id' => $user->id,
                    'ad_id' => null,
                    'ad_type' => 'mini',
                    'clicked_at' => now(),
                    'earnings' => $earnings,
                    'ip_address' => $request->ip(),
                    'is_valid' => true
                ]);
                
                // 100% a retiro
                $user->withdrawalWallet()->first()->addFunds($earnings, "Click mini-anuncio");
                
                AvailableAd::markAsUsed($user->id, 'mini');
                
            } elseif ($type === 'mega') {
                $earnings = 2000;
                
                UserAdClick::create([
                    'user_id' => $user->id,
                    'ad_id' => null,
                    'ad_type' => 'mega',
                    'clicked_at' => now(),
                    'earnings' => $earnings,
                    'ip_address' => $request->ip(),
                    'is_valid' => true
                ]);
                
                // 100% a retiro
                $user->withdrawalWallet()->first()->addFunds($earnings, "Click mega-anuncio");
                
                AvailableAd::markAsUsed($user->id, 'mega');
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'earnings' => $earnings
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
     * Pagar comisión al referidor por click del referido
     */
    private function payReferralCommission($user, $clickEarnings)
    {
        if (!$user->referred_by) {
            return;
        }
        
        $referrer = User::find($user->referred_by);
        if (!$referrer || !$referrer->currentRank) {
            return;
        }
        
        // Comisión según rango del referidor
        $commissionRates = [
            'Jade' => 100,
            'Perla' => 200,
            'Zafiro' => 300,
            'Rubí' => 400,
            'Esmeralda' => 400,
            'Diamante' => 400,
            'Diamante Azul' => 400,
            'Diamante Negro' => 400,
            'Diamante Corona' => 400,
        ];
        
        $commission = $commissionRates[$referrer->currentRank->name] ?? 100;
        
        // Agregar comisión a cartera de retiro del referidor
        $referrer->withdrawalWallet()->first()->addFunds(
            $commission, 
            "Comisión por click de {$user->name}"
        );
    }
}