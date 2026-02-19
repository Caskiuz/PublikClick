@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg p-6 text-white mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">⭐ Sistema Multinivel</h1>
                    <p class="text-yellow-100">Gana comisiones hasta 6 niveles de profundidad</p>
                </div>
                <div class="text-center">
                    <p class="text-sm">Tus Estrellas</p>
                    <div class="text-5xl">
                        @for($i = 0; $i < Auth::user()->stars; $i++)
                            ⭐
                        @endfor
                        @for($i = Auth::user()->stars; $i < 5; $i++)
                            ☆
                        @endfor
                    </div>
                    <p class="text-sm mt-2">{{ Auth::user()->stars }}/5 Estrellas</p>
                </div>
            </div>
        </div>

        @if(Auth::user()->currentRank->name !== 'DIAMANTE CORONA')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <p class="text-yellow-700 font-semibold">⚠️ Necesitas alcanzar Diamante Corona (40 invitados) para activar el sistema multinivel</p>
        </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-lg mb-2">Cómo Ganar Estrellas</h3>
                <ul class="text-sm space-y-2">
                    <li>⭐ 1 Estrella: 1 invitado directo con 40 invitados</li>
                    <li>⭐⭐ 2 Estrellas: 2 invitados directos con 40 invitados</li>
                    <li>⭐⭐⭐ 3 Estrellas: 3 invitados directos con 40 invitados</li>
                    <li>⭐⭐⭐⭐ 4 Estrellas: 4 invitados directos con 40 invitados</li>
                    <li>⭐⭐⭐⭐⭐ 5 Estrellas: 5 invitados directos con 40 invitados</li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-lg mb-2">Comisiones por Nivel</h3>
                <ul class="text-sm space-y-2">
                    <li>Nivel 2: $20 COP/click ($3,000/mes)</li>
                    <li>Nivel 3: $30 COP/click ($4,500/mes)</li>
                    <li>Nivel 4: $40 COP/click ($6,000/mes)</li>
                    <li>Nivel 5: $50 COP/click ($7,500/mes)</li>
                    <li>Nivel 6: $60 COP/click ($9,000/mes)</li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-lg mb-2">Ganancias Este Mes</h3>
                <div class="text-3xl font-bold text-green-600 mb-2">
                    ${{ number_format($totalMultilevel, 0, ',', '.') }}
                </div>
                <p class="text-xs text-gray-500">Comisiones multinivel</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">📊 Estadísticas por Nivel</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Nivel</th>
                            <th class="px-4 py-3 text-left">Estrellas Requeridas</th>
                            <th class="px-4 py-3 text-right">Usuarios</th>
                            <th class="px-4 py-3 text-right">Comisión/Click</th>
                            <th class="px-4 py-3 text-right">Ganado Este Mes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($multilevelStats as $level => $stats)
                        <tr class="{{ Auth::user()->stars >= ($level - 1) ? '' : 'opacity-50' }}">
                            <td class="px-4 py-3 font-bold">Nivel {{ $level }}</td>
                            <td class="px-4 py-3">
                                @for($i = 0; $i < ($level - 1); $i++)
                                    ⭐
                                @endfor
                                {{ $level - 1 }} {{ $level - 1 == 1 ? 'Estrella' : 'Estrellas' }}
                            </td>
                            <td class="px-4 py-3 text-right">{{ $stats['users_count'] }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format(match($level) { 2 => 20, 3 => 30, 4 => 40, 5 => 50, 6 => 60 }, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-green-600">
                                ${{ number_format($stats['monthly_earnings'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <h4 class="font-bold text-blue-800 mb-2">💡 Cómo Funciona</h4>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Debes estar en categoría Diamante Corona (40 invitados activos)</li>
                <li>• Ganas estrellas cuando tus invitados directos alcanzan 40 invitados</li>
                <li>• Cada estrella desbloquea un nivel adicional de comisiones</li>
                <li>• Las comisiones se pagan por cada click que hacen los usuarios en esos niveles</li>
                <li>• Máximo 5 estrellas = 6 niveles de profundidad (nivel 1 + niveles 2-6)</li>
            </ul>
        </div>
    </div>
</div>
@endsection
