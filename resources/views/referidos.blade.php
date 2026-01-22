@extends('layout')

@section('title', 'Referidos')
@section('page-title', 'Sistema de Referidos')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Total Referidos</h3>
                    <p class="text-2xl font-bold text-blue-600">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Comisiones</h3>
                    <p class="text-2xl font-bold text-green-600">$0.00</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-network-wired text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Nivel 1</h3>
                    <p class="text-2xl font-bold text-purple-600">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-sitemap text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Nivel 2-3</h3>
                    <p class="text-2xl font-bold text-orange-600">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Código de Referido -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-share-alt text-blue-600 mr-2"></i>
            Tu Código de Referido
        </h3>
        
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tu código único:</p>
                    <p class="text-2xl font-bold text-blue-600">{{ Auth::user()->referral_code ?? 'No asignado' }}</p>
                </div>
                <button onclick="copyToClipboard('{{ Auth::user()->referral_code }}')" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-copy mr-1"></i>
                    Copiar
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600 mb-2">Enlace de referido:</p>
                <div class="bg-gray-100 p-3 rounded border text-sm">
                    {{ url('/') }}?ref={{ Auth::user()->referral_code }}
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-2">Comparte en redes sociales:</p>
                <div class="flex space-x-2">
                    <button class="bg-blue-600 text-white p-2 rounded">
                        <i class="fab fa-facebook"></i>
                    </button>
                    <button class="bg-blue-400 text-white p-2 rounded">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button class="bg-green-600 text-white p-2 rounded">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Árbol de Referidos -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-sitemap text-blue-600 mr-2"></i>
            Árbol de Referidos
        </h3>
        
        <div class="text-center py-8">
            <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">Aún no tienes referidos</p>
            <p class="text-gray-400">Comparte tu código y comienza a ganar comisiones</p>
        </div>
    </div>

    <!-- Comisiones por Nivel -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-percentage text-blue-600 mr-2"></i>
            Comisiones por Nivel
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">20%</div>
                <h4 class="font-semibold mb-2">Nivel 1</h4>
                <p class="text-sm text-gray-600">Comisión por clicks de referidos directos</p>
            </div>
            
            <div class="border rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">10%</div>
                <h4 class="font-semibold mb-2">Nivel 2</h4>
                <p class="text-sm text-gray-600">Comisión por clicks de referidos de nivel 2</p>
            </div>
            
            <div class="border rounded-lg p-4 text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">5%</div>
                <h4 class="font-semibold mb-2">Nivel 3</h4>
                <p class="text-sm text-gray-600">Comisión por clicks de referidos de nivel 3</p>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Código copiado al portapapeles');
    });
}
</script>
@endsection