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
        
        return view('dashboard', array_merge(compact('user'), $data));
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
        $packages = \App\Models\Package::where('is_active', true)->orderBy('price_usd')->get();
        
        return view('paquetes', compact('user', 'packages'));
    }

    public function billetera()
    {
        $user = Auth::user();
        $user->load(['withdrawalWallet', 'donationWallet', 'transactions']);
        
        // Obtener métodos de pago activos
        $activePaymentMethods = \App\Models\PaymentGateway::where('is_active', true)->get();
        
        return view('billetera', compact('user', 'activePaymentMethods'));
    }

    public function estadisticas()
    {
        $user = Auth::user();
        $user->load(['currentPackage', 'currentRank', 'adClicks', 'activeReferrals']);
        
        // Clicks stats
        $totalClicks = $user->adClicks()->count();
        $todayClicks = $user->adClicks()->whereDate('clicked_at', today())->count();
        $weekClicks = $user->adClicks()->whereBetween('clicked_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthClicks = $user->adClicks()->whereMonth('clicked_at', now()->month)->whereYear('clicked_at', now()->year)->count();
        
        // Earnings stats
        $totalEarnings = ($user->withdrawalWallet->total_earned ?? 0) + ($user->donationWallet->total_earned ?? 0);
        $todayEarnings = $user->adClicks()->whereDate('clicked_at', today())->sum('earnings');
        $weekEarnings = $user->adClicks()->whereBetween('clicked_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('earnings');
        $monthEarnings = $user->adClicks()->whereMonth('clicked_at', now()->month)->whereYear('clicked_at', now()->year)->sum('earnings');
        
        // Referrals stats
        $level1 = $user->referrals()->count();
        $level2 = $user->referrals()->with('referrals')->get()->sum(fn($r) => $r->referrals->count());
        $level3 = $user->referrals()->with('referrals.referrals')->get()->sum(fn($r) => 
            $r->referrals->sum(fn($r2) => $r2->referrals->count())
        );
        
        // Last 7 days earnings
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $earnings = $user->adClicks()->whereDate('clicked_at', $date)->sum('earnings');
            $last7Days[] = [
                'date' => $date->format('d/m'),
                'earnings' => $earnings
            ];
        }
        
        $stats = [
            'total_clicks' => $totalClicks,
            'today_clicks' => $todayClicks,
            'week_clicks' => $weekClicks,
            'month_clicks' => $monthClicks,
            'total_earnings' => $totalEarnings,
            'today_earnings' => $todayEarnings,
            'week_earnings' => $weekEarnings,
            'month_earnings' => $monthEarnings,
            'referral_commissions' => $user->total_referral_earnings ?? 0,
            'active_referrals' => $user->active_referrals_count ?? 0,
            'level_1' => $level1,
            'level_2' => $level2,
            'level_3' => $level3,
            'last_7_days' => $last7Days
        ];
        
        return view('estadisticas', compact('user', 'stats'));
    }

    public function configuracion()
    {
        $user = Auth::user();
        return view('configuracion', compact('user'));
    }
    
    public function perfil()
    {
        $user = Auth::user();
        $activePaymentMethods = \App\Models\PaymentGateway::where('is_active', true)->get();
        return view('perfil', compact('user', 'activePaymentMethods'));
    }
    
    // Nuevos métodos
    public function miniAnuncios()
    {
        $user = Auth::user();
        return view('mini-anuncios', compact('user'));
    }
    
    public function megaAnuncios()
    {
        $user = Auth::user();
        return view('mega-anuncios', compact('user'));
    }
    
    public function tablero()
    {
        $user = Auth::user()->load(['withdrawalWallet', 'currentRank', 'activePackage']);
        $activeReferrals = \App\Services\RankService::getActiveReferralsCount($user->id);
        $todayClicks = $user->adClicks()->whereDate('clicked_at', today())->count();
        $monthlyEarnings = [
            'principal' => $user->adClicks()->where('ad_type', 'principal')->whereMonth('clicked_at', now()->month)->sum('earnings'),
            'mini' => $user->adClicks()->where('ad_type', 'mini')->whereMonth('clicked_at', now()->month)->sum('earnings'),
            'mega' => $user->adClicks()->where('ad_type', 'mega')->whereMonth('clicked_at', now()->month)->sum('earnings'),
            'commissions' => $user->transactions()->where('type', 'credit')->where('description', 'like', '%Comisión%')->whereMonth('created_at', now()->month)->sum('amount')
        ];
        $availableAds = [
            'principal' => \App\Models\UserAvailableAd::getTotalAvailable($user->id, 'principal'),
            'mini' => \App\Models\UserAvailableAd::getTotalAvailable($user->id, 'mini'),
            'mega' => \App\Models\UserAvailableAd::getTotalAvailable($user->id, 'mega'),
            'unlocked' => \App\Models\UserAvailableAd::getTotalAvailable($user->id, 'mini_unlocked')
        ];
        return view('tablero', compact('user', 'activeReferrals', 'todayClicks', 'monthlyEarnings', 'availableAds'));
    }
    
    public function multinivel()
    {
        $user = Auth::user();
        $multilevelStats = \App\Services\MultilevelService::getMultilevelStats($user->id);
        $totalMultilevel = \App\Models\MultilevelCommission::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        return view('multinivel', compact('user', 'multilevelStats', 'totalMultilevel'));
    }
    
    public function lider()
    {
        $user = Auth::user();
        $lider = $user->referrer;
        return view('lider', compact('user', 'lider'));
    }
    
    public function historialRetiros()
    {
        $user = Auth::user();
        $retiros = $user->transactions()->where('type', 'withdrawal')->orderBy('created_at', 'desc')->get();
        return view('historial-retiros', compact('user', 'retiros'));
    }
    
    public function amigos()
    {
        $user = Auth::user();
        return view('amigos', compact('user'));
    }
    
    public function testimonios()
    {
        $testimonios = \App\Models\Transaction::where('status', 'completed')
            ->whereNotNull('user_comment')
            ->with('user')
            ->orderBy('commented_at', 'desc')
            ->paginate(20);
        return view('testimonios', compact('testimonios'));
    }
    
    public function proyectosDonaciones()
    {
        $user = Auth::user();
        return view('proyectos-donaciones', compact('user'));
    }
    
    public function subeProyecto()
    {
        $user = Auth::user();
        return view('sube-proyecto', compact('user'));
    }
    
    public function misPaquetes()
    {
        $user = Auth::user()->load('activePackage');
        $activePackage = $user->activePackage;
        $purchaseDate = $user->package_purchased_at;
        $daysRemaining = $purchaseDate ? max(0, 30 - $purchaseDate->diffInDays(now())) : 0;
        $earnings = [
            'principal' => match($activePackage->price ?? 25) { 25 => 410, 50 => 610, 100 => 1130, 150 => 1610, default => 410 },
            'mini' => match($activePackage->price ?? 25) { 25 => 83.33, 50 => 425, 100 => 100, 150 => 600, default => 83.33 }
        ];
        $miniAdsCount = match($activePackage->price ?? 25) { 25 => 4, 50 => 4, 100 => 4, 150 => 8, default => 4 };
        $packageHistory = $user->transactions()->where('type', 'debit')->where('description', 'like', '%Paquete%')->get();
        return view('mis-paquetes', compact('user', 'activePackage', 'purchaseDate', 'daysRemaining', 'earnings', 'miniAdsCount', 'packageHistory'));
    }
    
    public function crearPTC()
    {
        $user = Auth::user();
        return view('crear-ptc', compact('user'));
    }
    
    public function crearBanner()
    {
        $user = Auth::user();
        return view('crear-banner', compact('user'));
    }
    
    public function recomiendaGana()
    {
        $user = Auth::user();
        return view('recomienda-gana', compact('user'));
    }
    
    public function mundoSorteos()
    {
        $user = Auth::user();
        // Obtener link de WhatsApp desde configuración
        $whatsappLink = \DB::table('settings')->where('key', 'mundo_sorteos_link')->value('value') ?? '#';
        return view('mundo-sorteos', compact('user', 'whatsappLink'));
    }
}