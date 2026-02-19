<?php

namespace App\Http\Controllers;

use App\Models\UserAvailableAd;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\UserAdClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    // Vista de anuncios principales
    public function principalAds()
    {
        $user = Auth::user()->load('activePackage');
        
        if (!$user->activePackage) {
            return redirect()->route('dashboard')->with('error', 'Necesitas un paquete activo para ver anuncios');
        }

        $available = UserAvailableAd::getTotalAvailable($user->id, UserAvailableAd::TYPE_PRINCIPAL);
        $earnings = $this->getPrincipalEarnings($user->activePackage->price);

        return view('ads.principal', compact('available', 'earnings'));
    }

    // Vista de mini-anuncios
    public function miniAds()
    {
        $user = Auth::user()->load('activePackage');
        
        if (!$user->activePackage) {
            return redirect()->route('dashboard')->with('error', 'Necesitas un paquete activo para ver anuncios');
        }

        $availableMini = UserAvailableAd::getTotalAvailable($user->id, UserAvailableAd::TYPE_MINI);
        $availableUnlocked = UserAvailableAd::getTotalAvailable($user->id, UserAvailableAd::TYPE_MINI_UNLOCKED);
        $earnings = $this->getMiniEarnings($user->activePackage->price);

        return view('ads.mini', compact('availableMini', 'availableUnlocked', 'earnings'));
    }

    // Vista de mega-anuncios
    public function megaAds()
    {
        $user = Auth::user()->load('activePackage');
        
        if (!$user->activePackage) {
            return redirect()->route('dashboard')->with('error', 'Necesitas un paquete activo para ver anuncios');
        }

        $available = UserAvailableAd::getTotalAvailable($user->id, UserAvailableAd::TYPE_MEGA);

        return view('ads.mega', compact('available'));
    }

    // Procesar click en anuncio
    public function processClick(Request $request)
    {
        $request->validate([
            'ad_type' => 'required|in:principal,mini,mega,mini_unlocked',
            'captcha_answer' => 'required'
        ]);

        $user = Auth::user()->load('activePackage');

        if (!$user->activePackage) {
            return response()->json(['success' => false, 'message' => 'Paquete inactivo'], 403);
        }

        // Validar CAPTCHA
        if (!$this->validateCaptcha($request->captcha_answer, session('captcha_answer'))) {
            return response()->json(['success' => false, 'message' => 'CAPTCHA incorrecto'], 400);
        }

        try {
            // Consumir anuncio
            UserAvailableAd::consumeAd($user->id, $request->ad_type);

            // Calcular ganancias
            $earnings = $this->calculateEarnings($request->ad_type, $user->activePackage->price);

            // Registrar en billetera
            $this->registerEarnings($user, $earnings, $request->ad_type);

            // Registrar click
            UserAdClick::create([
                'user_id' => $user->id,
                'ad_id' => null,
                'ad_type' => $request->ad_type,
                'clicked_at' => now(),
                'earnings' => $earnings['total'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Procesar comisión para referidor nivel 1
            if ($user->referred_by) {
                \App\Services\RankService::processReferralCommission($user->id, $request->ad_type);
            }

            // Procesar comisiones multinivel (niveles 2-6)
            \App\Services\MultilevelService::processMultilevelCommissions($user->id);

            // Actualizar rango y estrellas del usuario
            \App\Services\RankService::updateUserRank($user->id);
            \App\Services\MultilevelService::updateStars($user->id);

            return response()->json([
                'success' => true,
                'message' => "¡Has ganado $" . number_format($earnings['total'], 0, ',', '.') . " COP!",
                'earnings' => $earnings
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // Generar CAPTCHA
    public function generateCaptcha()
    {
        $captchas = [
            ['type' => 'color', 'question' => '¿De qué color es el cielo?', 'options' => ['Azul', 'Rojo', 'Verde', 'Amarillo'], 'answer' => 'Azul'],
            ['type' => 'math', 'question' => '¿Cuánto es 5 + 3?', 'options' => ['6', '7', '8', '9'], 'answer' => '8'],
            ['type' => 'shape', 'question' => '¿Cuántos lados tiene un triángulo?', 'options' => ['2', '3', '4', '5'], 'answer' => '3'],
            ['type' => 'color', 'question' => '¿De qué color es una manzana roja?', 'options' => ['Azul', 'Rojo', 'Verde', 'Amarillo'], 'answer' => 'Rojo'],
            ['type' => 'math', 'question' => '¿Cuánto es 10 - 4?', 'options' => ['4', '5', '6', '7'], 'answer' => '6'],
        ];

        $captcha = $captchas[array_rand($captchas)];
        session(['captcha_answer' => $captcha['answer']]);

        return response()->json($captcha);
    }

    // Validar CAPTCHA
    private function validateCaptcha($userAnswer, $correctAnswer)
    {
        return strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer));
    }

    private function calculateEarnings($adType, $packagePrice)
    {
        $config = \App\Services\EconomicConfig::class;
        $earnings = ['withdrawal' => 0, 'donation' => 0, 'total' => 0];

        switch ($adType) {
            case 'principal':
                $earnings['withdrawal'] = $config::getMainAdValue($packagePrice);
                $earnings['donation'] = $config::MAIN_AD_DONATION;
                $earnings['total'] = $earnings['withdrawal'] + $earnings['donation'];
                break;

            case 'mini':
                $earnings['withdrawal'] = $config::getMiniAdValue($packagePrice);
                $earnings['total'] = $earnings['withdrawal'];
                break;

            case 'mega':
                $earnings['withdrawal'] = $config::MEGA_AD_VALUE;
                $earnings['total'] = $config::MEGA_AD_VALUE;
                break;

            case 'mini_unlocked':
                $earnings['withdrawal'] = $config::UNLOCKED_MINI_VALUE;
                $earnings['total'] = $config::UNLOCKED_MINI_VALUE;
                break;
        }

        return $earnings;
    }

    // Registrar ganancias en billetera
    private function registerEarnings($user, $earnings, $adType)
    {
        $withdrawalWallet = $user->wallets()->where('type', Wallet::TYPE_WITHDRAWAL)->first();
        $donationWallet = $user->wallets()->where('type', Wallet::TYPE_DONATION)->first();

        if ($earnings['withdrawal'] > 0 && $withdrawalWallet) {
            $withdrawalWallet->addFunds($earnings['withdrawal'], "Anuncio {$adType} completado");
        }

        if ($earnings['donation'] > 0 && $donationWallet) {
            $donationWallet->addFunds($earnings['donation'], "Donación de anuncio {$adType}");
        }
    }

    private function getPrincipalEarnings($packagePrice)
    {
        return \App\Services\EconomicConfig::getMainAdValue($packagePrice) + \App\Services\EconomicConfig::MAIN_AD_DONATION;
    }

    private function getMiniEarnings($packagePrice)
    {
        return \App\Services\EconomicConfig::getMiniAdValue($packagePrice);
    }
}
