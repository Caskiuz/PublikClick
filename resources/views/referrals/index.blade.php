@extends('layout')

@section('title', 'Red de Referidos')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">🌐 Mi Red de Referidos</h2>
            <p class="text-gray-600">Gestiona y visualiza tu red MLM</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Total Referidos</p>
                    <h3 class="text-3xl font-bold">{{ $stats['total_referrals'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">Referidos Activos</p>
                    <h3 class="text-3xl font-bold">{{ $stats['active_referrals'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Comisiones Totales</p>
                    <h3 class="text-3xl font-bold">{{ formatCurrency($stats['total_commissions'], $user->currency) }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm mb-1">Tu Rango</p>
                    <h3 class="text-2xl font-bold">{{ $user->currentRank->name ?? 'Sin rango' }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-crown text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Link -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-link text-blue-600 mr-2"></i>
            Tu Enlace de Referido
        </h3>
        <div class="flex gap-2">
            <input type="text" id="referralLink" 
                   value="{{ route('register') }}?ref={{ $user->referral_code }}" 
                   readonly
                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono text-sm">
            <button onclick="copyLink()" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors flex items-center">
                <i class="fas fa-copy mr-2"></i>
                Copiar
            </button>
        </div>
        <p class="text-sm text-gray-600 mt-2">Código: <span class="font-semibold text-blue-600">{{ $user->referral_code }}</span></p>
    </div>

    <!-- Levels Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Nivel 1 (Directos)</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ $stats['level_1'] }}</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Nivel 2</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $stats['level_2'] }}</h3>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Nivel 3</p>
                    <h3 class="text-3xl font-bold text-purple-600">{{ $stats['level_3'] }}</h3>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-sitemap text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Tree -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-sitemap text-blue-600 mr-2"></i>
                Árbol Genealógico
            </h3>
        </div>
        <div class="p-6">
            @if(count($tree) > 0)
                <div class="tree-container">
                    <ul class="tree">
                        <li>
                            <div class="tree-node tree-root">
                                <div class="font-semibold">{{ $user->name }}</div>
                                <div class="text-xs mt-1">{{ $user->currentRank->name ?? 'Sin rango' }}</div>
                            </div>
                            @if(count($tree) > 0)
                                <ul>
                                    @foreach($tree as $node)
                                        @include('referrals.tree-node', ['node' => $node])
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    </ul>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Aún no tienes referidos</h4>
                    <p class="text-gray-500">¡Comparte tu enlace y comienza a construir tu red!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.tree-container { overflow-x: auto; padding: 20px; }
.tree, .tree ul { list-style: none; margin: 0; padding: 0; position: relative; }
.tree ul { padding-top: 20px; }
.tree li { display: table-cell; padding: 10px 5px 0 5px; position: relative; text-align: center; vertical-align: top; }
.tree li::before { content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #e5e7eb; width: 50%; height: 10px; }
.tree li::after { content: ''; position: absolute; top: 0; right: 50%; border-left: 2px solid #e5e7eb; height: 10px; }
.tree li:only-child::before, .tree li:only-child::after { display: none; }
.tree li:first-child::before { border: 0 none; }
.tree li:last-child::before { border-right: 2px solid #e5e7eb; border-radius: 0 5px 0 0; }
.tree li:first-child::after { border-radius: 5px 0 0 0; }
.tree ul ul::before { content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #e5e7eb; width: 0; height: 10px; }
.tree-node { border: 2px solid #e5e7eb; border-radius: 8px; padding: 12px 16px; display: inline-block; background: white; min-width: 140px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s; }
.tree-node:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); transform: translateY(-2px); }
.tree-root { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-color: #667eea; font-weight: 600; }
.tree-node.level-1 { border-color: #3b82f6; background: #eff6ff; }
.tree-node.level-2 { border-color: #10b981; background: #f0fdf4; }
.tree-node.level-3 { border-color: #f59e0b; background: #fffbeb; }
.tree-node.inactive { opacity: 0.5; border-style: dashed; }
</style>

<script>
function copyLink() {
    const input = document.getElementById('referralLink');
    input.select();
    document.execCommand('copy');
    
    Swal.fire({
        icon: 'success',
        title: '¡Copiado!',
        text: 'Enlace copiado al portapapeles',
        timer: 2000,
        showConfirmButton: false
    });
}
</script>
@endsection
