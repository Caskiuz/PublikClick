@extends('layout')

@section('title', 'Configuración de Pagos')
@section('page-title', 'Pasarelas de Pago')

@section('content')
<div class="space-y-6">
    <!-- FIAT Gateways -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-credit-card text-blue-600 mr-2"></i>
            Pasarelas FIAT
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($gateways->where('gateway_type', 'fiat') as $gateway)
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-lg">{{ $gateway->gateway_name }}</h4>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" 
                               onchange="toggleGateway({{ $gateway->id }})"
                               {{ $gateway->is_active ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                <form onsubmit="updateGateway(event, {{ $gateway->id }})" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                        <input type="text" name="api_key" value="{{ $gateway->api_key }}" 
                               class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API Secret</label>
                        <input type="password" name="api_secret" value="{{ $gateway->api_secret }}" 
                               class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Secret</label>
                        <input type="text" name="webhook_secret" value="{{ $gateway->webhook_secret }}" 
                               class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg">
                        Guardar Configuración
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Crypto Gateways -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-bitcoin text-orange-500 mr-2"></i>
            Pasarelas Crypto
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($gateways->where('gateway_type', 'crypto') as $gateway)
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-lg">{{ $gateway->gateway_name }}</h4>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" 
                               onchange="toggleGateway({{ $gateway->id }})"
                               {{ $gateway->is_active ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </label>
                </div>
                <form onsubmit="updateGateway(event, {{ $gateway->id }})" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Wallet Address</label>
                        <input type="text" name="wallet_address" value="{{ $gateway->wallet_address }}" 
                               class="w-full px-3 py-2 border rounded-lg text-sm font-mono">
                    </div>
                    @if($gateway->network)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Network</label>
                        <input type="text" name="network" value="{{ $gateway->network }}" readonly
                               class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-50">
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API Key (Opcional)</label>
                        <input type="text" name="api_key" value="{{ $gateway->api_key }}" 
                               class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-lg">
                        Guardar Configuración
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
async function toggleGateway(gatewayId) {
    try {
        const response = await fetch(`/admin/payment-settings/${gatewayId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Éxito', data.message, 'success');
        }
    } catch (error) {
        showNotification('Error', 'Error al cambiar estado', 'error');
    }
}

async function updateGateway(event, gatewayId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch(`/admin/payment-settings/${gatewayId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Éxito', result.message, 'success');
        }
    } catch (error) {
        showNotification('Error', 'Error al guardar configuración', 'error');
    }
}

function showNotification(title, message, type) {
    alert(`${title}: ${message}`);
}
</script>
@endsection
