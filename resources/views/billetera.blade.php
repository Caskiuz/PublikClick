@extends('layout')

@section('title', 'Billetera')
@section('page-title', 'Mi Billetera')

@section('content')
<div class="space-y-6">
    <!-- Balance -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Cartera Retiro</h3>
                    <p class="text-4xl font-bold">${{ number_format($user->wallet_balance ?? 0, 2) }}</p>
                    <p class="text-green-100 mt-2">Disponible para retirar</p>
                </div>
                <div class="text-right">
                    <i class="fas fa-wallet text-6xl text-green-200"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Cartera Donación</h3>
                    <p class="text-4xl font-bold">$0.00</p>
                    <p class="text-blue-100 mt-2">Acumulado en donaciones</p>
                </div>
                <div class="text-right">
                    <i class="fas fa-heart text-6xl text-blue-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                Solicitar Retiro
            </h3>
            <form id="withdrawalForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Monto a retirar</label>
                    <input type="number" id="amount" step="0.01" min="10" max="{{ $user->wallet_balance ?? 0 }}" 
                           placeholder="Mínimo $10.00" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono Nequi</label>
                    <input type="text" id="nequi_phone" placeholder="3001234567" maxlength="10" required
                           value="{{ $user->nequi_phone }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar contraseña</label>
                    <input type="password" id="password" placeholder="Tu contraseña" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Solicitar Retiro
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                Resumen de Ganancias
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total ganado:</span>
                    <span class="font-semibold">${{ number_format($user->wallet_balance ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Cartera retiro:</span>
                    <span class="font-semibold text-green-600">${{ number_format($user->wallet_balance ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Cartera donación:</span>
                    <span class="font-semibold text-blue-600">$0.00</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total retirado:</span>
                    <span class="font-semibold text-red-600">$0.00</span>
                </div>
                <hr>
                <div class="flex justify-between items-center font-bold text-lg">
                    <span>Balance actual:</span>
                    <span class="text-green-600">${{ number_format($user->wallet_balance ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Transacciones -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-history text-blue-600 mr-2"></i>
            Historial de Retiros
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($user->transactions->where('type', 'withdrawal') as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                            ${{ number_format(abs($transaction->amount), 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <i class="fas fa-mobile-alt mr-1"></i>
                            Nequi
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaction->status === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            @elseif($transaction->status === 'completed')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Completado
                                </span>
                            @elseif($transaction->status === 'cancelled')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Cancelado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($transaction->status === 'pending')
                                <button onclick="cancelWithdrawal({{ $transaction->id }})" 
                                        class="text-red-600 hover:text-red-900 font-semibold">
                                    Cancelar
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-receipt text-gray-300 text-4xl mb-2"></i>
                            <p>No hay retiros registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Información de Retiros -->
    <div class="bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Información Importante
        </h3>
        <ul class="text-blue-700 space-y-2 text-sm">
            <li>• Monto mínimo de retiro: $10.00</li>
            <li>• Los retiros se procesan en 24-48 horas hábiles</li>
            <li>• Solo se puede retirar desde la Cartera Retiro</li>
            <li>• Máximo 3 retiros por semana</li>
            <li>• Debes tener tu teléfono Nequi actualizado</li>
        </ul>
    </div>
</div>

<script>
document.getElementById('withdrawalForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        amount: document.getElementById('amount').value,
        nequi_phone: document.getElementById('nequi_phone').value,
        password: document.getElementById('password').value,
        _token: '{{ csrf_token() }}'
    };
    
    try {
        const response = await fetch('/withdrawals/request', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Error al procesar la solicitud');
    }
});

async function cancelWithdrawal(transactionId) {
    if (!confirm('¿Estás seguro de cancelar este retiro?')) return;
    
    try {
        const response = await fetch(`/withdrawals/${transactionId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Error al cancelar el retiro');
    }
}
</script>
@endsection