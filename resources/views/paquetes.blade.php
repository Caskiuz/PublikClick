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
    </div>

    <!-- Paquetes Disponibles -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-6 flex items-center">
            <i class="fas fa-box text-blue-600 mr-2"></i>
            Paquetes Disponibles
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Básico -->
            <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 transition-colors">
                <div class="text-center">
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Básico</h4>
                    <div class="text-3xl font-bold text-blue-600 mb-4">$25</div>
                    <ul class="text-left space-y-2 mb-6 text-sm">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            5 clicks diarios
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            $0.10 por click
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Comisiones nivel 1
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Soporte básico
                        </li>
                    </ul>
                    <button onclick="window.location.href='/payments/1/select-method'" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition-colors">
                        Adquirir Paquete
                    </button>
                </div>
            </div>

            <!-- Premium -->
            <div class="border-2 border-green-500 rounded-xl p-6 relative">
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</span>
                </div>
                <div class="text-center">
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Premium</h4>
                    <div class="text-3xl font-bold text-green-600 mb-4">$50</div>
                    <ul class="text-left space-y-2 mb-6 text-sm">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            5 clicks diarios
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            $0.15 por click
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Comisiones 2 niveles
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Soporte prioritario
                        </li>
                    </ul>
                    <button onclick="window.location.href='/payments/2/select-method'" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition-colors">
                        Adquirir Paquete
                    </button>
                </div>
            </div>

            <!-- VIP -->
            <div class="border-2 border-yellow-500 rounded-xl p-6">
                <div class="text-center">
                    <h4 class="text-xl font-bold text-gray-800 mb-2">VIP</h4>
                    <div class="text-3xl font-bold text-yellow-600 mb-4">$100</div>
                    <ul class="text-left space-y-2 mb-6 text-sm">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            5 clicks diarios
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            $0.20 por click
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Comisiones 3 niveles
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Soporte VIP
                        </li>
                    </ul>
                    <button onclick="window.location.href='/payments/3/select-method'" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-lg transition-colors">
                        Adquirir Paquete
                    </button>
                </div>
            </div>

            <!-- Elite -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6">
                <div class="text-center">
                    <h4 class="text-xl font-bold mb-2">Elite</h4>
                    <div class="text-3xl font-bold mb-4">$200</div>
                    <ul class="text-left space-y-2 mb-6 text-sm">
                        <li class="flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            5 clicks diarios
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            $0.25 por click
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Comisiones 3 niveles
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Mini-anuncios extra
                        </li>
                    </ul>
                    <button onclick="window.location.href='/payments/5/select-method'" class="w-full bg-white text-purple-600 font-bold py-3 rounded-lg hover:bg-gray-100 transition-colors">
                        Adquirir Paquete
                    </button>
                </div>
            </div>
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
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Básico</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Premium</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">VIP</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Elite</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 font-medium">Clicks Diarios</td>
                        <td class="px-6 py-4 text-center">5</td>
                        <td class="px-6 py-4 text-center">5</td>
                        <td class="px-6 py-4 text-center">5</td>
                        <td class="px-6 py-4 text-center">5</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-medium">Ganancia por Click</td>
                        <td class="px-6 py-4 text-center">$0.10</td>
                        <td class="px-6 py-4 text-center">$0.15</td>
                        <td class="px-6 py-4 text-center">$0.20</td>
                        <td class="px-6 py-4 text-center">$0.25</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-medium">Niveles de Comisión</td>
                        <td class="px-6 py-4 text-center">1</td>
                        <td class="px-6 py-4 text-center">2</td>
                        <td class="px-6 py-4 text-center">3</td>
                        <td class="px-6 py-4 text-center">3</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 font-medium">Mini-anuncios</td>
                        <td class="px-6 py-4 text-center">❌</td>
                        <td class="px-6 py-4 text-center">❌</td>
                        <td class="px-6 py-4 text-center">❌</td>
                        <td class="px-6 py-4 text-center">✅</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection