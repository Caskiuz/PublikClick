@extends('layout')

@section('title', 'Referidos')
@section('page-title', 'Mi Red de Referidos')

@section('content')
<div class="space-y-6">
    <!-- Stats de Referidos -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Total Referidos</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $user->referrals->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Activos</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $user->getActiveReferralsCount() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-crown text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Mi Rango</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $user->currentRank->name ?? 'Jade' }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Comisión/Click</h3>
                    <p class="text-2xl font-bold text-yellow-600">${{ $user->currentRank->referral_commission ?? 100 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Código de Referido -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow p-6 text-white">
        <h3 class="text-xl font-semibold mb-4">Tu Código de Referido</h3>
        <div class="flex items-center space-x-4">
            <div class="flex-1">
                <input type="text" id="referralCode" value="{{ $user->referral_code }}" readonly 
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70">
            </div>
            <button onclick="copyReferralCode()" 
                    class="bg-white/20 hover:bg-white/30 px-6 py-3 rounded-lg transition-colors">
                <i class="fas fa-copy mr-2"></i>Copiar
            </button>
        </div>
        <p class="mt-3 text-blue-100">Comparte este código y gana ${{ $user->currentRank->referral_commission ?? 100 }} por cada click de tus referidos</p>
    </div>

    <!-- Lista de Referidos -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-sitemap text-blue-600 mr-2"></i>
            Mi Red de Referidos
        </h3>
        
        @if($user->referrals->count() > 0)
            <div class="space-y-4">
                @foreach($user->referrals as $referral)
                <div class="border rounded-lg p-4 {{ $referral->currentPackage ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($referral->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $referral->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $referral->email }}</p>
                                <p class="text-xs text-gray-500">Registrado: {{ $referral->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($referral->currentPackage)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ $referral->currentPackage->name }}
                                </span>
                                <p class="text-sm text-gray-600 mt-1">Rango: {{ $referral->currentRank->name ?? 'Jade' }}</p>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Sin paquete
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                <h4 class="text-xl font-semibold text-gray-600 mb-2">Aún no tienes referidos</h4>
                <p class="text-gray-500 mb-6">Comparte tu código de referido y comienza a ganar comisiones</p>
                <button onclick="copyReferralCode()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg">
                    <i class="fas fa-share mr-2"></i>Compartir Código
                </button>
            </div>
        @endif
    </div>

    <!-- Información de Comisiones -->
    <div class="bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Cómo Funciona el Sistema de Referidos
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-blue-700">
            <div>
                <h4 class="font-semibold mb-2">Ganancias por Referidos:</h4>
                <ul class="space-y-1 text-sm">
                    <li>• Jade (0-2): $100 por click</li>
                    <li>• Perla (3-5): $200 por click</li>
                    <li>• Zafiro (6-9): $300 por click</li>
                    <li>• Rubí+ (10+): $400 por click</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Requisitos:</h4>
                <ul class="space-y-1 text-sm">
                    <li>• El referido debe tener paquete activo</li>
                    <li>• Ganas por cada click que hagan (5 diarios)</li>
                    <li>• Tu rango determina la comisión</li>
                    <li>• Pagos automáticos a tu cartera</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function copyReferralCode() {
    const codeInput = document.getElementById('referralCode');
    codeInput.select();
    document.execCommand('copy');
    
    // Mostrar mensaje de éxito
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check mr-2"></i>¡Copiado!';
    button.classList.add('bg-green-500');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('bg-green-500');
    }, 2000);
}
</script>
@endsection