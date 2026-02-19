@extends('layout')

@section('title', 'Mi Perfil')
@section('page-title', 'Mi Perfil')

@section('content')
<div class="space-y-6">
    <!-- Header con foto -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center gap-6">
            <div class="relative">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-4xl font-bold text-blue-600">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <button class="absolute bottom-0 right-0 bg-white text-blue-600 rounded-full p-2 shadow-lg hover:bg-gray-100">
                    <i class="fas fa-camera text-sm"></i>
                </button>
            </div>
            <div>
                <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                <p class="text-blue-100">{{ Auth::user()->email }}</p>
                <p class="text-sm text-blue-200 mt-1">Código de referido: <span class="font-semibold">{{ Auth::user()->referral_code }}</span></p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <button onclick="showTab('datos')" id="tab-datos" class="tab-button active whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fas fa-user mr-2"></i>Datos Personales
                </button>
                <button onclick="showTab('seguridad')" id="tab-seguridad" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fas fa-lock mr-2"></i>Seguridad
                </button>
                <button onclick="showTab('pagos')" id="tab-pagos" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fas fa-wallet mr-2"></i>Métodos de Pago
                </button>
                <button onclick="showTab('preferencias')" id="tab-preferencias" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    <i class="fas fa-cog mr-2"></i>Preferencias
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Datos Personales -->
            <div id="content-datos" class="tab-content">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Información Personal</h3>
                <form class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" readonly
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                            <input type="tel" name="phone" placeholder="3001234567" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">País</label>
                            <select name="country" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option>Colombia</option>
                                <option>México</option>
                                <option>Argentina</option>
                                <option>Perú</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg">
                        Guardar Cambios
                    </button>
                </form>
            </div>

            <!-- Seguridad -->
            <div id="content-seguridad" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Cambiar Contraseña</h3>
                <form class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                        <input type="password" name="current_password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg">
                        Actualizar Contraseña
                    </button>
                </form>
            </div>

            <!-- Métodos de Pago -->
            <div id="content-pagos" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Mis Métodos de Pago</h3>
                <p class="text-sm text-gray-600 mb-4">Configura tus cuentas para recibir retiros</p>
                
                @if($activePaymentMethods->count() > 0)
                <!-- FIAT -->
                @if($activePaymentMethods->where('type', 'fiat')->count() > 0)
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                        Métodos FIAT
                    </h4>
                    <div class="space-y-4">
                    @foreach($activePaymentMethods->where('type', 'fiat') as $method)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                @php
                                    $icon = match($method->code) {
                                        'nequi', 'daviplata' => 'mobile-alt',
                                        'paypal' => 'paypal',
                                        'stripe' => 'stripe',
                                        'mercadopago' => 'shopping-cart',
                                        default => 'university'
                                    };
                                @endphp
                                <i class="fas fa-{{ $icon }} text-2xl text-blue-600"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $method->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ ucfirst($method->category) }}</p>
                                </div>
                            </div>
                            <button onclick="configurePayment('{{ $method->code }}')" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                <i class="fas fa-edit mr-1"></i>Configurar
                            </button>
                        </div>
                        <div id="config-{{ $method->code }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                            <form class="space-y-3">
                                @csrf
                                <input type="hidden" name="method" value="{{ $method->code }}">
                                @if(in_array($method->code, ['nequi', 'daviplata']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                        <input type="text" name="phone" placeholder="3001234567" maxlength="10" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                @elseif($method->code === 'paypal')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email PayPal</label>
                                        <input type="email" name="email" placeholder="usuario@email.com" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                @elseif($method->code === 'mercadopago')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Mercado Pago</label>
                                        <input type="email" name="email" placeholder="usuario@email.com" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de Cuenta</label>
                                        <input type="text" name="account" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cuenta</label>
                                        <select name="account_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                            <option>Ahorros</option>
                                            <option>Corriente</option>
                                        </select>
                                    </div>
                                @endif
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    Guardar
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    </div>
                </div>
                @endif
                
                <!-- CRYPTO -->
                @if($activePaymentMethods->where('type', 'crypto')->count() > 0)
                <div>
                    <h4 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-bitcoin mr-2 text-orange-600"></i>
                        Métodos CRYPTO
                    </h4>
                    <div class="space-y-4">
                        @foreach($activePaymentMethods->where('type', 'crypto') as $method)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-500 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <i class="fab fa-bitcoin text-2xl text-orange-600"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $method->name }}</h4>
                                        <p class="text-xs text-gray-500">{{ ucfirst($method->category) }}</p>
                                    </div>
                                </div>
                                <button onclick="configurePayment('{{ $method->code }}')" class="text-orange-600 hover:text-orange-700 font-semibold text-sm">
                                    <i class="fas fa-edit mr-1"></i>Configurar
                                </button>
                            </div>
                            <div id="config-{{ $method->code }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                                <form class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="method" value="{{ $method->code }}">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección de Wallet</label>
                                        <input type="text" name="wallet_address" placeholder="0x..." 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 font-mono text-sm">
                                        <p class="text-xs text-gray-500 mt-1">Ingresa tu dirección de wallet para recibir {{ $method->name }}</p>
                                    </div>
                                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                        Guardar
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-info-circle text-4xl mb-3 opacity-30"></i>
                    <p>No hay métodos de pago disponibles</p>
                    <p class="text-sm mt-1">Contacta al administrador</p>
                </div>
                @endif
            </div>

            <!-- Preferencias -->
            <div id="content-preferencias" class="tab-content hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Preferencias del Sistema</h3>
                <form class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Moneda de Visualización</label>
                        <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            @foreach(\App\Helpers\CurrencyHelper::getAvailableCurrencies() as $code => $name)
                                <option value="{{ $code }}" {{ (Auth::user()->currency ?? 'COP') === $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Los valores se convertirán automáticamente</p>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Todos los valores internos se manejan en COP. La conversión es solo para visualización.
                        </p>
                    </div>
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-6 rounded-lg">
                        Guardar Preferencias
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.tab-button {
    color: #6b7280;
    border-color: transparent;
    transition: all 0.3s;
}
.tab-button:hover {
    color: #3b82f6;
    border-color: #e5e7eb;
}
.tab-button.active {
    color: #3b82f6;
    border-color: #3b82f6;
}
</style>

<script>
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(button => button.classList.remove('active'));
    document.getElementById('content-' + tabName).classList.remove('hidden');
    document.getElementById('tab-' + tabName).classList.add('active');
}

function configurePayment(method) {
    const config = document.getElementById('config-' + method);
    const allConfigs = document.querySelectorAll('[id^="config-"]');
    allConfigs.forEach(c => {
        if (c.id !== 'config-' + method) c.classList.add('hidden');
    });
    config.classList.toggle('hidden');
}
</script>
@endsection
