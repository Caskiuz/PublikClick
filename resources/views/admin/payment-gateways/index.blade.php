@extends('layout')

@section('title', 'Métodos de Pago')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">💳 Gestión de Métodos de Pago</h2>
            <p class="text-gray-600">Configura los métodos de pago disponibles para usuarios</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    <!-- FIAT Payment Methods -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                Métodos FIAT (Moneda Tradicional)
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($fiat as $gateway)
                <div class="border rounded-lg p-4 {{ $gateway->is_active ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $gateway->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $gateway->description }}</p>
                            <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full {{ $gateway->category === 'wallet' ? 'bg-blue-100 text-blue-700' : ($gateway->category === 'bank' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($gateway->category) }}
                            </span>
                        </div>
                        <form action="{{ route('admin.payment-gateways.toggle', $gateway->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-sm rounded {{ $gateway->is_active ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }}">
                                {{ $gateway->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </div>
                    @if($gateway->is_active)
                    <button onclick="openConfig('{{ $gateway->id }}', '{{ $gateway->name }}', '{{ $gateway->code }}')" class="w-full mt-2 px-3 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                        <i class="fas fa-cog mr-1"></i> Configurar
                    </button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CRYPTO Payment Methods -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-bitcoin text-orange-500 mr-2"></i>
                Métodos CRYPTO (Criptomonedas)
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($crypto as $gateway)
                <div class="border rounded-lg p-4 {{ $gateway->is_active ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }}">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $gateway->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $gateway->description }}</p>
                            <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full {{ $gateway->category === 'stablecoin' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ ucfirst($gateway->category) }}
                            </span>
                        </div>
                        <form action="{{ route('admin.payment-gateways.toggle', $gateway->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-sm rounded {{ $gateway->is_active ? 'bg-orange-500 text-white' : 'bg-gray-300 text-gray-700' }}">
                                {{ $gateway->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </div>
                    @if($gateway->is_active)
                    <button onclick="openConfig('{{ $gateway->id }}', '{{ $gateway->name }}', '{{ $gateway->code }}')" class="w-full mt-2 px-3 py-2 bg-orange-500 text-white text-sm rounded hover:bg-orange-600">
                        <i class="fas fa-cog mr-1"></i> Configurar
                    </button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Config Modal -->
<div id="configModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold" id="modalTitle"></h3>
            <button onclick="closeConfig()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="configForm" method="POST">
            @csrf
            @method('PUT')
            <div id="configFields" class="space-y-4"></div>
            <div class="mt-6 flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Guardar Configuración
                </button>
                <button type="button" onclick="closeConfig()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const configTemplates = {
    nequi: [
        {name: 'phone', label: 'Teléfono Nequi', type: 'text', placeholder: '3001234567'},
        {name: 'account_name', label: 'Nombre de la cuenta', type: 'text'}
    ],
    daviplata: [
        {name: 'phone', label: 'Teléfono Daviplata', type: 'text', placeholder: '3001234567'},
        {name: 'account_name', label: 'Nombre de la cuenta', type: 'text'}
    ],
    paypal: [
        {name: 'email', label: 'Email PayPal', type: 'email'},
        {name: 'client_id', label: 'Client ID', type: 'text'},
        {name: 'secret', label: 'Secret Key', type: 'password'}
    ],
    stripe: [
        {name: 'public_key', label: 'Public Key', type: 'text'},
        {name: 'secret_key', label: 'Secret Key', type: 'password'}
    ],
    usdt_trc20: [
        {name: 'wallet_address', label: 'Dirección Wallet TRC-20', type: 'text', placeholder: 'T...'}
    ],
    usdt_erc20: [
        {name: 'wallet_address', label: 'Dirección Wallet ERC-20', type: 'text', placeholder: '0x...'}
    ],
    usdt_bep20: [
        {name: 'wallet_address', label: 'Dirección Wallet BEP-20', type: 'text', placeholder: '0x...'}
    ],
    btc: [
        {name: 'wallet_address', label: 'Dirección Bitcoin', type: 'text', placeholder: 'bc1...'}
    ],
    eth: [
        {name: 'wallet_address', label: 'Dirección Ethereum', type: 'text', placeholder: '0x...'}
    ],
    default: [
        {name: 'api_key', label: 'API Key', type: 'text'},
        {name: 'api_secret', label: 'API Secret', type: 'password'},
        {name: 'account_id', label: 'Account ID / Email / Phone', type: 'text'}
    ]
};

function openConfig(id, name, code) {
    document.getElementById('modalTitle').textContent = `Configurar ${name}`;
    document.getElementById('configForm').action = `/admin/payment-gateways/${id}`;
    
    const fields = configTemplates[code] || configTemplates.default;
    const container = document.getElementById('configFields');
    container.innerHTML = '';
    
    fields.forEach(field => {
        container.innerHTML += `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">${field.label}</label>
                <input type="${field.type}" name="${field.name}" placeholder="${field.placeholder || ''}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        `;
    });
    
    document.getElementById('configModal').classList.remove('hidden');
}

function closeConfig() {
    document.getElementById('configModal').classList.add('hidden');
}
</script>
@endsection
