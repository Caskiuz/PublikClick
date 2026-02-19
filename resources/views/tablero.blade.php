@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">📊 Tablero de Control</h1>
            <p class="text-indigo-100">Resumen de tu actividad y ganancias</p>
        </div>

        <div class="grid md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-600">Balance Total</h3>
                    <span class="text-2xl">💰</span>
                </div>
                <p class="text-3xl font-bold text-green-600">${{ number_format(Auth::user()->withdrawalWallet->balance ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-1">COP</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-600">Invitados Activos</h3>
                    <span class="text-2xl">👥</span>
                </div>
                <p class="text-3xl font-bold text-blue-600">{{ $activeReferrals }}</p>
                <p class="text-xs text-gray-500 mt-1">Referidos</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-600">Categoría Actual</h3>
                    <span class="text-2xl">🏆</span>
                </div>
                <p class="text-2xl font-bold text-purple-600">{{ Auth::user()->currentRank->name ?? 'Jade' }}</p>
                <p class="text-xs text-gray-500 mt-1">Rango</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-600">Clicks Hoy</h3>
                    <span class="text-2xl">🖱️</span>
                </div>
                <p class="text-3xl font-bold text-orange-600">{{ $todayClicks }}</p>
                <p class="text-xs text-gray-500 mt-1">Anuncios</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4">📈 Ganancias del Mes</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                        <span class="text-sm text-gray-700">Anuncios Principales</span>
                        <span class="font-bold text-green-600">${{ number_format($monthlyEarnings['principal'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                        <span class="text-sm text-gray-700">Mini-Anuncios</span>
                        <span class="font-bold text-blue-600">${{ number_format($monthlyEarnings['mini'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                        <span class="text-sm text-gray-700">Mega-Anuncios</span>
                        <span class="font-bold text-yellow-600">${{ number_format($monthlyEarnings['mega'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded">
                        <span class="text-sm text-gray-700">Comisiones</span>
                        <span class="font-bold text-purple-600">${{ number_format($monthlyEarnings['commissions'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4">🎯 Anuncios Disponibles</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700">Principales (90s)</span>
                        <span class="font-bold text-blue-600">{{ $availableAds['principal'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700">Mini-Anuncios (60s)</span>
                        <span class="font-bold text-green-600">{{ $availableAds['mini'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700">Mega-Anuncios (120s)</span>
                        <span class="font-bold text-orange-600">{{ $availableAds['mega'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="text-sm text-gray-700">Mini Desbloqueados</span>
                        <span class="font-bold text-purple-600">{{ $availableAds['unlocked'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold mb-4">🚀 Acciones Rápidas</h3>
            <div class="grid md:grid-cols-4 gap-4">
                <a href="{{ route('anuncios-principales') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition">
                    <div class="text-3xl mb-2">📺</div>
                    <p class="font-semibold">Ver Anuncios</p>
                </a>
                <a href="{{ route('recomienda-gana') }}" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition">
                    <div class="text-3xl mb-2">🔗</div>
                    <p class="font-semibold">Invitar Amigos</p>
                </a>
                <a href="{{ route('billetera') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg text-center transition">
                    <div class="text-3xl mb-2">💰</div>
                    <p class="font-semibold">Mi Billetera</p>
                </a>
                <a href="{{ route('paquetes') }}" class="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-lg text-center transition">
                    <div class="text-3xl mb-2">📦</div>
                    <p class="font-semibold">Paquetes</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
