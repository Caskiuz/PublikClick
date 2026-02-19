@extends('layout')

@section('title', 'Paquetes')
@section('page-title', 'Paquetes Publicitarios')

@section('content')
<div class="space-y-6">
    <!-- Paquete Actual -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-crown text-yellow-500 mr-2"></i>
            Tu Paquete Actual
        </h3>
        
        @if($user->currentPackage)
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border-2 border-green-500">
            <div class="text-center">
                <h4 class="text-2xl font-bold text-gray-800 mb-2">{{ $user->currentPackage->name }}</h4>
                <p class="text-gray-600 mb-4">Activo desde {{ $user->package_purchased_at->format('d/m/Y') }}</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-600">Clicks Diarios</p>
                        <p class="text-2xl font-bold text-green-600">5</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ganancia por Click</p>
                        <p class="text-2xl font-bold text-green-600">{{ formatCurrency($user->calculateMainAdEarnings(), $user->currency) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Mini-Anuncios</p>
                        <p class="text-2xl font-bold text-green-600">{{ $user->currentRank->mini_ads_daily ?? 1 }}/día</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Estado</p>
                        <p class="text-2xl font-bold text-green-600">Activo</p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="text-center">
                <h4 class="text-2xl font-bold text-gray-800 mb-2">Sin Paquete</h4>
                <p class="text-gray-600 mb-4">Adquiere un paquete para comenzar a ganar dinero</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-500">Clicks Diarios</p>
                        <p class="text-xl font-bold">0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ganancia por Click</p>
                        <p class="text-xl font-bold">$0.00</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Niveles de Comisión</p>
                        <p class="text-xl font-bold">0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Estado</p>
                        <p class="text-xl font-bold text-red-600">Inactivo</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Paquetes Disponibles -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-6 flex items-center">
            <i class="fas fa-box text-blue-600 mr-2"></i>
            Paquetes Disponibles
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($packages as $index => $package)
            <div class="{{ $index === 1 ? 'border-2 border-green-500' : ($index === 3 ? 'bg-gradient-to-br from-purple-500 to-purple-600 text-white' : 'border-2 border-gray-200') }} rounded-xl p-6 hover:shadow-lg transition-all relative">
                @if($index === 1)
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</span>
                </div>
                @endif
                <div class="text-center">
                    <h4 class="text-xl font-bold mb-2 {{ $index === 3 ? 'text-white' : 'text-gray-800' }}">{{ $package->name }}</h4>
                    <div class="text-3xl font-bold mb-4 {{ $index === 0 ? 'text-blue-600' : ($index === 1 ? 'text-green-600' : ($index === 2 ? 'text-yellow-600' : 'text-white')) }}">
                        ${{ $package->price_usd }}
                    </div>
                    <ul class="text-left space-y-2 mb-6 text-sm">
                        <li class="flex items-center">
                            <i class="fas fa-check {{ $index === 3 ? 'text-white' : 'text-green-500' }} mr-2"></i>
                            5 clicks diarios
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check {{ $index === 3 ? 'text-white' : 'text-green-500' }} mr-2"></i>
                            {{ formatCurrency($package->main_ad_value, $user->currency) }} por click
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check {{ $index === 3 ? 'text-white' : 'text-green-500' }} mr-2"></i>
                            {{ $package->mini_ads_count }} mini-anuncios
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check {{ $index === 3 ? 'text-white' : 'text-green-500' }} mr-2"></i>
                            Soporte {{ $index === 3 ? 'VIP' : ($index === 2 ? 'prioritario' : 'básico') }}
                        </li>
                    </ul>
                    @if($user->current_package_id === $package->id)
                    <button disabled class="w-full bg-gray-400 text-white font-bold py-3 rounded-lg cursor-not-allowed">
                        Paquete Actual
                    </button>
                    @else
                    <button onclick="purchasePackage({{ $package->id }})" class="w-full {{ $index === 3 ? 'bg-white text-purple-600 hover:bg-gray-100' : 'bg-blue-500 hover:bg-blue-600 text-white' }} font-bold py-3 rounded-lg transition-colors">
                        Adquirir Paquete
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Comparación de Beneficios -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-balance-scale text-blue-600 mr-2"></i>
            Comparación de Beneficios
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Característica</th>
                        @foreach($packages as $package)
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ $package->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 font-medium">Clicks Diarios</td>
                        @foreach($packages as $package)
                        <td class="px-6 py-4 text-center">5</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-medium">Ganancia por Click</td>
                        @foreach($packages as $package)
                        <td class="px-6 py-4 text-center">{{ formatCurrency($package->main_ad_value, $user->currency) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-medium">Mini-anuncios</td>
                        @foreach($packages as $package)
                        <td class="px-6 py-4 text-center">{{ $package->mini_ads_count }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-medium">Mega-anuncios</td>
                        @foreach($packages as $package)
                        <td class="px-6 py-4 text-center">✅</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
async function purchasePackage(packageId) {
    const result = await Swal.fire({
        title: '¿Confirmar compra?',
        text: 'Serás redirigido al proceso de pago',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    });
    
    if (result.isConfirmed) {
        window.location.href = `/payments/${packageId}/select-method`;
    }
}
</script>

@endsection