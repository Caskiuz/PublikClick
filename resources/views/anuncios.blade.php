@extends('layout')

@section('title', 'Anuncios')
@section('page-title', 'Anuncios')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-mouse-pointer text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Clicks Hoy</h3>
                    <p class="text-2xl font-bold text-blue-600">0/5</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Ganado Hoy</h3>
                    <p class="text-2xl font-bold text-green-600">$0.00</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Disponibles</h3>
                    <p class="text-2xl font-bold text-purple-600">5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Anuncios Disponibles -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-bullhorn text-blue-600 mr-2"></i>
            Anuncios Disponibles para Hoy
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-3">
                    <img src="https://via.placeholder.com/50" alt="Anuncio" class="w-12 h-12 rounded mr-3">
                    <div>
                        <h4 class="font-semibold">Producto Demo 1</h4>
                        <p class="text-sm text-gray-500">Categoría: Tecnología</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-3">Descubre este increíble producto y gana dinero por hacer click.</p>
                <div class="flex justify-between items-center">
                    <span class="text-green-600 font-bold text-lg">$0.10</span>
                    <button onclick="alert('¡Ganaste $0.10!')" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        <i class="fas fa-mouse-pointer mr-1"></i>
                        Click
                    </button>
                </div>
            </div>

            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-3">
                    <img src="https://via.placeholder.com/50" alt="Anuncio" class="w-12 h-12 rounded mr-3">
                    <div>
                        <h4 class="font-semibold">Servicio Demo 2</h4>
                        <p class="text-sm text-gray-500">Categoría: Servicios</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-3">Conoce este servicio premium y obtén recompensas.</p>
                <div class="flex justify-between items-center">
                    <span class="text-green-600 font-bold text-lg">$0.15</span>
                    <button onclick="alert('¡Ganaste $0.15!')" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        <i class="fas fa-mouse-pointer mr-1"></i>
                        Click
                    </button>
                </div>
            </div>

            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-3">
                    <img src="https://via.placeholder.com/50" alt="Anuncio" class="w-12 h-12 rounded mr-3">
                    <div>
                        <h4 class="font-semibold">Oferta Demo 3</h4>
                        <p class="text-sm text-gray-500">Categoría: Ofertas</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm mb-3">Aprovecha esta oferta especial limitada.</p>
                <div class="flex justify-between items-center">
                    <span class="text-green-600 font-bold text-lg">$0.20</span>
                    <button onclick="alert('¡Ganaste $0.20!')" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        <i class="fas fa-mouse-pointer mr-1"></i>
                        Click
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Clicks -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-history text-blue-600 mr-2"></i>
            Historial de Clicks
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anuncio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ganancia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No hay clicks registrados hoy
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection