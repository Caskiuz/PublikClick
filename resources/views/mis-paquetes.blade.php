@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">📦 Mis Paquetes</h1>
            <p class="text-blue-100">Historial de paquetes adquiridos</p>
        </div>

        @if($activePackage)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-2 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-green-600">✅ Paquete Activo</h3>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">ACTIVO</span>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Paquete</p>
                    <p class="text-lg font-bold">${{ $activePackage->price }} USD</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Fecha de Compra</p>
                    <p class="text-lg font-bold">{{ $purchaseDate->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Días Restantes</p>
                    <p class="text-lg font-bold text-green-600">{{ $daysRemaining }} días</p>
                </div>
            </div>
            <div class="mt-4 bg-blue-50 p-4 rounded">
                <p class="text-sm text-blue-700"><strong>Beneficios:</strong></p>
                <ul class="text-sm text-blue-700 mt-2 space-y-1">
                    <li>• 5 anuncios principales diarios (${{ number_format($earnings['principal'], 0, ',', '.') }} COP c/u)</li>
                    <li>• {{ $miniAdsCount }} mini-anuncios diarios (${{ number_format($earnings['mini'], 0, ',', '.') }} COP c/u)</li>
                    <li>• Comisiones por referidos activos</li>
                </ul>
            </div>
        </div>
        @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <p class="text-yellow-700 font-semibold">⚠️ No tienes un paquete activo</p>
            <p class="text-sm text-yellow-600 mt-1">Adquiere un paquete para comenzar a ganar</p>
            <a href="{{ route('paquetes') }}" class="inline-block mt-3 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg">
                Ver Paquetes Disponibles
            </a>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">📜 Historial de Paquetes</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paquete</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($packageHistory as $purchase)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $purchase->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm font-semibold">Paquete ${{ $purchase->amount }} USD</td>
                            <td class="px-4 py-3 text-sm">${{ number_format($purchase->amount, 2) }} USD</td>
                            <td class="px-4 py-3 text-center">
                                @if($purchase->status == 'completed')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">✓ Completado</span>
                                @else
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Expirado</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No hay historial de paquetes</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
