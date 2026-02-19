@extends('layout')

@section('title', 'Estadísticas')
@section('page-title', 'Estadísticas y Reportes')

@section('content')
<div class="space-y-6">
    <!-- Resumen General -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-mouse-pointer text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Total Clicks</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_clicks']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Ganancias Totales</h3>
                    <p class="text-2xl font-bold text-green-600">{{ formatCurrency($stats['total_earnings'], $user->currency) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Referidos Activos</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['active_referrals'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-percentage text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Comisiones</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ formatCurrency($stats['referral_commissions'], $user->currency) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                Ganancias por Día (Últimos 7 días)
            </h3>
            @if($stats['total_clicks'] > 0)
            <div class="h-64">
                <canvas id="earningsChart"></canvas>
            </div>
            @else
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
                <div class="text-center">
                    <i class="fas fa-chart-line text-gray-300 text-4xl mb-2"></i>
                    <p class="text-gray-500">Sin datos para mostrar</p>
                </div>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                Distribución de Ganancias
            </h3>
            @if($stats['total_clicks'] > 0)
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Clicks Principales</span>
                        <span class="font-semibold">{{ formatCurrency($stats['month_earnings'] * 0.7, $user->currency) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Mini-Anuncios</span>
                        <span class="font-semibold">{{ formatCurrency($stats['month_earnings'] * 0.2, $user->currency) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 20%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Comisiones</span>
                        <span class="font-semibold">{{ formatCurrency($stats['referral_commissions'], $user->currency) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 10%"></div>
                    </div>
                </div>
            </div>
            @else
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
                <div class="text-center">
                    <i class="fas fa-chart-pie text-gray-300 text-4xl mb-2"></i>
                    <p class="text-gray-500">Sin datos para mostrar</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas Detalladas -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-table text-blue-600 mr-2"></i>
            Estadísticas Detalladas
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border rounded-lg p-4">
                <h4 class="font-semibold mb-3 text-blue-600">Actividad de Clicks</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Hoy:</span>
                        <span class="font-semibold">{{ $stats['today_clicks'] }}/5</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Esta semana:</span>
                        <span class="font-semibold">{{ $stats['week_clicks'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Este mes:</span>
                        <span class="font-semibold">{{ $stats['month_clicks'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total:</span>
                        <span class="font-semibold">{{ $stats['total_clicks'] }}</span>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <h4 class="font-semibold mb-3 text-green-600">Ganancias</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Hoy:</span>
                        <span class="font-semibold">{{ formatCurrency($stats['today_earnings'], $user->currency) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Esta semana:</span>
                        <span class="font-semibold">{{ formatCurrency($stats['week_earnings'], $user->currency) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Este mes:</span>
                        <span class="font-semibold">{{ formatCurrency($stats['month_earnings'], $user->currency) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total:</span>
                        <span class="font-semibold">{{ formatCurrency($stats['total_earnings'], $user->currency) }}</span>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <h4 class="font-semibold mb-3 text-purple-600">Red de Referidos</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Nivel 1:</span>
                        <span class="font-semibold">{{ $stats['level_1'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Nivel 2:</span>
                        <span class="font-semibold">{{ $stats['level_2'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Nivel 3:</span>
                        <span class="font-semibold">{{ $stats['level_3'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total:</span>
                        <span class="font-semibold">{{ $stats['level_1'] + $stats['level_2'] + $stats['level_3'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ranking -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-trophy text-yellow-500 mr-2"></i>
            Tu Posición en el Ranking
        </h3>
        
        @if($stats['total_clicks'] > 0)
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 text-center border-2 border-yellow-500">
            <div class="text-6xl text-yellow-500 mb-4">
                <i class="fas fa-medal"></i>
            </div>
            <h4 class="text-2xl font-bold text-gray-800 mb-2">Rango: {{ $user->currentRank->name ?? 'Jade' }}</h4>
            <p class="text-gray-600">{{ $stats['total_clicks'] }} clicks totales | {{ formatCurrency($stats['total_earnings'], $user->currency) }} ganados</p>
        </div>
        @else
        <div class="bg-gray-50 rounded-lg p-6 text-center">
            <div class="text-6xl text-gray-300 mb-4">
                <i class="fas fa-medal"></i>
            </div>
            <h4 class="text-2xl font-bold text-gray-600 mb-2">Sin Ranking</h4>
            <p class="text-gray-500">Comienza a hacer clicks para aparecer en el ranking</p>
        </div>
        @endif
    </div>

    <!-- Metas y Objetivos -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-target text-red-600 mr-2"></i>
            Metas y Objetivos
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border rounded-lg p-4">
                <h4 class="font-semibold mb-3">Meta Diaria</h4>
                <div class="mb-2">
                    <div class="flex justify-between text-sm mb-1">
                        <span>Clicks realizados</span>
                        <span>{{ $stats['today_clicks'] }}/5</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($stats['today_clicks'] / 5) * 100 }}%"></div>
                    </div>
                </div>
                <p class="text-sm text-gray-600">¡Completa tus 5 clicks diarios!</p>
            </div>

            <div class="border rounded-lg p-4">
                <h4 class="font-semibold mb-3">Meta Mensual</h4>
                <div class="mb-2">
                    <div class="flex justify-between text-sm mb-1">
                        <span>Ganancias</span>
                        <span>{{ formatCurrency($stats['month_earnings'], $user->currency) }}/{{ formatCurrency(50000, $user->currency) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(($stats['month_earnings'] / 50000) * 100, 100) }}%"></div>
                    </div>
                </div>
                <p class="text-sm text-gray-600">Meta: {{ formatCurrency(50000, $user->currency) }} este mes</p>
            </div>
        </div>
    </div>
</div>

@if($stats['total_clicks'] > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('earningsChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($stats['last_7_days'], 'date')) !!},
            datasets: [{
                label: 'Ganancias',
                data: {!! json_encode(array_column($stats['last_7_days'], 'earnings')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}
</script>
@endif
@endsection