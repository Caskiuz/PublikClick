@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">📜 Historial de Retiros</h1>
            <p class="text-purple-100">Todas tus solicitudes de retiro</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Solicitud</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Procesado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($withdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-mono">#{{ $withdrawal->id }}</td>
                            <td class="px-4 py-3 text-sm">{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm font-bold text-green-600">${{ number_format($withdrawal->amount, 0, ',', '.') }} COP</td>
                            <td class="px-4 py-3 text-sm">
                                @if($withdrawal->payment_method)
                                <span class="capitalize">{{ str_replace('_', ' ', $withdrawal->payment_method) }}</span>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($withdrawal->status == 'completed')
                                <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full font-semibold">✓ Completado</span>
                                @elseif($withdrawal->status == 'pending')
                                <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full font-semibold">⏳ Pendiente</span>
                                @else
                                <span class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded-full font-semibold">✗ Rechazado</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($withdrawal->processed_at)
                                {{ $withdrawal->processed_at->format('d/m/Y H:i') }}
                                @else
                                <span class="text-gray-400">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="text-6xl mb-4">📭</div>
                                <p class="text-gray-500 text-lg">No has realizado retiros aún</p>
                                <a href="{{ route('billetera') }}" class="inline-block mt-4 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg">
                                    Solicitar Primer Retiro
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($withdrawals->hasPages())
            <div class="mt-6">
                {{ $withdrawals->links() }}
            </div>
            @endif
        </div>

        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <h4 class="font-bold text-blue-800 mb-2">ℹ️ Información sobre Retiros</h4>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Los retiros se procesan en 24-48 horas hábiles</li>
                <li>• Debes esperar 30 días entre cada retiro</li>
                <li>• El costo de transferencia lo asume el usuario</li>
                <li>• Verifica que tus datos bancarios sean correctos</li>
            </ul>
        </div>
    </div>
</div>
@endsection
