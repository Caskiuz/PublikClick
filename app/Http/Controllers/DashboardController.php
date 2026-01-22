<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ad;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        
        // Cache de datos del usuario por 5 minutos
        $cacheKey = 'user_dashboard_' . $user->id;
        $data = \Cache::remember($cacheKey, 300, function() use ($user) {
            $user->load(['currentPackage', 'currentRank', 'withdrawalWallet', 'donationWallet', 'activeReferrals']);
            
            return [
                'todayMainClicks' => $user->adClicks()->whereDate('clicked_at', today())->where('ad_type', 'main')->count(),
                'todayMiniClicks' => $user->adClicks()->whereDate('clicked_at', today())->where('ad_type', 'mini')->count(),
                'megaAd' => $user->getCurrentMegaAd(),
                'availableAds' => \App\Models\Ad::where('is_active', true)->inRandomOrder()->limit(5)->get()
            ];
        });
        
        return view('dashboard-simple', array_merge(compact('user'), $data));
    }

    public function anuncios()
    {
        $user = Auth::user();
        $user->load(['currentPackage', 'currentRank']);
        
        $todayMainClicks = $user->adClicks()->whereDate('clicked_at', today())->where('ad_type', 'main')->count();
        $todayMiniClicks = $user->adClicks()->whereDate('clicked_at', today())->where('ad_type', 'mini')->count();
        $megaAd = $user->getCurrentMegaAd();
        $availableAds = Ad::where('is_active', true)->inRandomOrder()->limit(5)->get();
        
        return view('anuncios', compact('user', 'todayMainClicks', 'todayMiniClicks', 'megaAd', 'availableAds'));
    }

    public function referidos()
    {
        $user = Auth::user();
        $user->load(['referrals.currentPackage', 'currentRank']);
        
        return view('referidos', compact('user'));
    }

    public function paquetes()
    {
        $user = Auth::user();
        $user->load(['currentPackage', 'currentRank']);
        
        return view('paquetes', compact('user'));
    }

    public function billetera()
    {
        $user = Auth::user();
        $user->load(['withdrawalWallet', 'donationWallet', 'transactions']);
        
        return view('billetera', compact('user'));
    }

    public function estadisticas()
    {
        $user = Auth::user();
        $user->load(['currentPackage', 'currentRank', 'adClicks', 'activeReferrals']);
        
        return view('estadisticas', compact('user'));
    }

    public function configuracion()
    {
        $user = Auth::user();
        return view('configuracion', compact('user'));
    }
}