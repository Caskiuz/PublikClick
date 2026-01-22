<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\User;
use App\Models\UserAdClick;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdController extends Controller
{
    public function click(Request $request, $adId)
    {
        $user = Auth::user();
        $ad = Ad::findOrFail($adId);
        
        // Validaciones
        if (!$user->canClickAds()) {
            return response()->json([
                'success' => false,
                'message' => 'Ya completaste tus 5 clicks diarios'
            ]);
        }
        
        if ($ad->hasBeenClickedByUser($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Ya hiciste click en este anuncio hoy'
            ]);
        }
        
        if (!$ad->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Este anuncio no está activo'
            ]);
        }
        
        DB::beginTransaction();
        
        try {
            // Calcular ganancias según el paquete del usuario (por ahora valor fijo)
            $earnings = $ad->click_value;
            
            // Registrar el click
            UserAdClick::recordClick($user->id, $ad->id, $earnings);
            
            // Registrar transacción de ganancia
            Transaction::recordClickEarning($user->id, $earnings, $ad->id);
            
            // Actualizar balance del usuario
            $user->increment('wallet_balance', $earnings);
            
            // Procesar comisiones por referidos
            $this->processReferralCommissions($user, $earnings);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "¡Ganaste $" . number_format($earnings, 4) . "!",
                'earnings' => $earnings,
                'new_balance' => $user->fresh()->wallet_balance,
                'clicks_remaining' => 5 - $user->getTodayClicksCount()
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el click. Inténtalo de nuevo.'
            ]);
        }
    }
    
    private function processReferralCommissions(User $user, $earnings)
    {
        $referrer = $user->referrer;
        $level = 1;
        
        while ($referrer && $level <= 3) {
            // Comisiones fijas por nivel por ahora
            $commissionRates = [1 => 0.30, 2 => 0.15, 3 => 0.05];
            $commission = $earnings * $commissionRates[$level];
            
            // Registrar comisión
            Transaction::recordReferralCommission(
                $referrer->id, 
                $commission, 
                $user->id, 
                $level
            );
            
            // Actualizar balance del referidor
            $referrer->increment('wallet_balance', $commission);
            
            $referrer = $referrer->referrer;
            $level++;
        }
    }
    
    public function getAvailable()
    {
        $user = Auth::user();
        
        $availableAds = Ad::where('is_active', true)
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('ad_id')
                    ->from('user_ad_clicks')
                    ->where('user_id', $user->id)
                    ->whereDate('clicked_at', today());
            })
            ->limit(5 - $user->getTodayClicksCount())
            ->get();
            
        return response()->json([
            'ads' => $availableAds,
            'clicks_remaining' => 5 - $user->getTodayClicksCount()
        ]);
    }
}
