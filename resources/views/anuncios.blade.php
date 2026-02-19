@extends('layout')

@section('title', 'Anuncios')
@section('page-title', 'Anuncios')

@section('content')
<div class="space-y-6">
    <!-- Estadísticas Superiores -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-mouse-pointer text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase">Clicks Hoy</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $todayMainClicks }}/5</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase">Ganado Hoy</h3>
                    <p class="text-2xl font-bold text-green-600">{{ formatCurrency(convertCurrency($user->adClicks()->whereDate('clicked_at', today())->sum('earnings')), $user->currency ?? 'USD') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Ganado</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ formatCurrency(convertCurrency($user->wallet_balance), $user->currency ?? 'USD') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-fire text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase">Racha</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ $user->adClicks()->whereDate('clicked_at', '>=', now()->subDays(7))->distinct('clicked_at')->count('clicked_at') }} días</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sistema de Clicks -->
    @if($user->currentPackage)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Ads -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-bullhorn mr-2"></i>
                    Anuncios Principales
                </h3>
                <span class="bg-white text-blue-600 px-3 py-1 rounded-full text-sm font-bold">
                    {{ 5 - $todayMainClicks }}/5
                </span>
            </div>
            <div class="text-center mb-4">
                <p class="text-5xl font-bold mb-2">{{ 5 - $todayMainClicks }}</p>
                <p class="text-blue-100 mb-1">Clicks disponibles</p>
                <p class="text-2xl font-bold">{{ formatCurrency(convertCurrency($user->calculateMainAdEarnings())) }}</p>
                <p class="text-blue-100 text-sm">por cada click</p>
            </div>
            @if($todayMainClicks < 5 && $availableAds->count() > 0)
                <button onclick="clickMainAd({{ $availableAds->first()->id }})" 
                        class="w-full bg-white text-blue-600 hover:bg-blue-50 font-bold py-3 px-4 rounded-lg transition-all transform hover:scale-105">
                    <i class="fas fa-mouse-pointer mr-2"></i>
                    Hacer Click Ahora
                </button>
            @else
                <button disabled class="w-full bg-blue-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed opacity-50">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ $todayMainClicks >= 5 ? 'Completado por hoy' : 'Sin anuncios' }}
                </button>
            @endif
        </div>

        <!-- Mini Ads -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-star mr-2"></i>
                    Mini-Anuncios
                </h3>
                <span class="bg-white text-yellow-600 px-3 py-1 rounded-full text-sm font-bold">
                    {{ ($user->currentRank->mini_ads_daily ?? 1) - $todayMiniClicks }}/{{ $user->currentRank->mini_ads_daily ?? 1 }}
                </span>
            </div>
            <div class="text-center mb-4">
                <p class="text-5xl font-bold mb-2">{{ ($user->currentRank->mini_ads_daily ?? 1) - $todayMiniClicks }}</p>
                <p class="text-yellow-100 mb-1">Clicks disponibles</p>
                <p class="text-2xl font-bold">{{ formatCurrency(convertCurrency($user->calculateMiniAdEarnings())) }}</p>
                <p class="text-yellow-100 text-sm">por cada click</p>
            </div>
            @if($todayMiniClicks < ($user->currentRank->mini_ads_daily ?? 1))
                <button onclick="clickMiniAd()" 
                        class="w-full bg-white text-yellow-600 hover:bg-yellow-50 font-bold py-3 px-4 rounded-lg transition-all transform hover:scale-105">
                    <i class="fas fa-star mr-2"></i>
                    Hacer Click Ahora
                </button>
            @else
                <button disabled class="w-full bg-yellow-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed opacity-50">
                    <i class="fas fa-check-circle mr-2"></i>
                    Completado por hoy
                </button>
            @endif
        </div>

        <!-- Mega Ads -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-gem mr-2"></i>
                    Mega-Anuncios
                </h3>
                <span class="bg-white text-purple-600 px-3 py-1 rounded-full text-sm font-bold">
                    {{ $megaAd->clicks_remaining ?? ($user->currentRank->mega_ads_monthly ?? 10) }}
                </span>
            </div>
            <div class="text-center mb-4">
                <p class="text-5xl font-bold mb-2">{{ $megaAd->clicks_remaining ?? ($user->currentRank->mega_ads_monthly ?? 10) }}</p>
                <p class="text-purple-100 mb-1">Clicks este mes</p>
                <p class="text-2xl font-bold">{{ formatCurrency(convertCurrency(2000)) }}</p>
                <p class="text-purple-100 text-sm">por cada click</p>
            </div>
            @if(!$megaAd || $megaAd->clicks_remaining > 0)
                <button onclick="clickMegaAd()" 
                        class="w-full bg-white text-purple-600 hover:bg-purple-50 font-bold py-3 px-4 rounded-lg transition-all transform hover:scale-105">
                    <i class="fas fa-gem mr-2"></i>
                    Hacer Click Ahora
                </button>
            @else
                <button disabled class="w-full bg-purple-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed opacity-50">
                    <i class="fas fa-calendar-times mr-2"></i>
                    Agotado este mes
                </button>
            @endif
        </div>
    </div>
    @else
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm">
                    <strong>¡Compra un paquete para comenzar!</strong>
                    Necesitas un paquete activo para hacer clicks y ganar dinero.
                    <a href="{{ route('paquetes') }}" class="underline font-semibold">Ver paquetes disponibles</a>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Historial de Clicks -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-xl font-semibold flex items-center">
                <i class="fas fa-history text-blue-600 mr-2"></i>
                Historial de Clicks
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ganancia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($user->adClicks()->latest()->take(10)->get() as $click)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($click->ad_type === 'main')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-bullhorn mr-1"></i> Principal
                                </span>
                            @elseif($click->ad_type === 'mini')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i> Mini
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-gem mr-1"></i> Mega
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $click->clicked_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                            {{ formatCurrency(convertCurrency($click->earnings), $user->currency ?? 'USD') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Pagado
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                            <p>No hay clicks registrados aún</p>
                            <p class="text-sm">¡Haz tu primer click arriba!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Estadísticas Mensuales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                Resumen del Mes
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                    <span class="text-gray-700">Clicks Principales</span>
                    <span class="font-bold text-blue-600">{{ $user->adClicks()->whereMonth('clicked_at', now()->month)->where('ad_type', 'main')->count() }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                    <span class="text-gray-700">Mini-Clicks</span>
                    <span class="font-bold text-yellow-600">{{ $user->adClicks()->whereMonth('clicked_at', now()->month)->where('ad_type', 'mini')->count() }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded">
                    <span class="text-gray-700">Mega-Clicks</span>
                    <span class="font-bold text-purple-600">{{ $user->adClicks()->whereMonth('clicked_at', now()->month)->where('ad_type', 'mega')->count() }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded border-t-2 border-green-500">
                    <span class="text-gray-700 font-semibold">Total Ganado</span>
                    <span class="font-bold text-green-600 text-lg">{{ formatCurrency(convertCurrency($user->adClicks()->whereMonth('clicked_at', now()->month)->sum('earnings')), $user->currency ?? 'USD') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                Logros y Progreso
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-fire text-orange-500 text-xl mr-3"></i>
                        <span class="text-gray-700">Racha Actual</span>
                    </div>
                    <span class="font-bold text-orange-600">{{ $user->adClicks()->whereDate('clicked_at', '>=', now()->subDays(7))->distinct('clicked_at')->count('clicked_at') }} días</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-crown text-purple-500 text-xl mr-3"></i>
                        <span class="text-gray-700">Rango Actual</span>
                    </div>
                    <span class="font-bold text-purple-600">{{ $user->currentRank->name ?? 'Sin rango' }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-users text-blue-500 text-xl mr-3"></i>
                        <span class="text-gray-700">Referidos Activos</span>
                    </div>
                    <span class="font-bold text-blue-600">{{ $user->getActiveReferralsCount() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function clickMainAd(adId) {
        try {
            const response = await fetch(`/clicks/main/${adId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Modal.success(data.message, '¡Ganaste!');
                setTimeout(() => location.reload(), 2000);
            } else {
                Modal.error(data.message, 'Error');
            }
        } catch (error) {
            Modal.error('Error al procesar el click', 'Error');
        }
    }

    async function clickMiniAd() {
        try {
            const response = await fetch('/clicks/mini', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Modal.success(data.message, '¡Ganaste!');
                setTimeout(() => location.reload(), 2000);
            } else {
                Modal.error(data.message, 'Error');
            }
        } catch (error) {
            Modal.error('Error al procesar el click', 'Error');
        }
    }

    async function clickMegaAd() {
        try {
            const response = await fetch('/clicks/mega', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Modal.success(data.message, '¡MEGA-CLICK!');
                setTimeout(() => location.reload(), 2000);
            } else {
                Modal.error(data.message, 'Error');
            }
        } catch (error) {
            Modal.error('Error al procesar el click', 'Error');
        }
    }
</script>
@endsection