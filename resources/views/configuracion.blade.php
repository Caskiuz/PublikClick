@extends('layout')

@section('title', 'Configuración')
@section('page-title', 'Configuración de Cuenta')

@section('content')
<div class="space-y-6">
    <!-- Información Personal -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-user text-blue-600 mr-2"></i>
            Información Personal
        </h3>
        
        <form class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                    <input type="text" value="{{ Auth::user()->name }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" value="{{ Auth::user()->email }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                    <input type="tel" placeholder="Ingresa tu teléfono" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">País</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Colombia</option>
                        <option>México</option>
                        <option>Argentina</option>
                        <option>Perú</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg">
                Actualizar Información
            </button>
        </form>
    </div>

    <!-- Cambiar Contraseña -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-lock text-green-600 mr-2"></i>
            Cambiar Contraseña
        </h3>
        
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                <input type="password" placeholder="Ingresa tu contraseña actual" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                    <input type="password" placeholder="Nueva contraseña" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                    <input type="password" placeholder="Confirma la nueva contraseña" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg">
                Cambiar Contraseña
            </button>
        </form>
    </div>

    <!-- Información Bancaria -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-university text-purple-600 mr-2"></i>
            Información Bancaria
        </h3>
        
        <form class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Banco</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option>Selecciona tu banco</option>
                        <option>Nequi</option>
                        <option>Bancolombia</option>
                        <option>Daviplata</option>
                        <option>Banco de Bogotá</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de Cuenta</label>
                    <input type="text" placeholder="Número de cuenta o celular" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cuenta</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option>Ahorros</option>
                        <option>Corriente</option>
                        <option>Nequi</option>
                        <option>Daviplata</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titular de la Cuenta</label>
                    <input type="text" placeholder="Nombre del titular" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
            </div>
            
            <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-6 rounded-lg">
                Guardar Información Bancaria
            </button>
        </form>
    </div>

    <!-- Notificaciones -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center">
            <i class="fas fa-bell text-yellow-600 mr-2"></i>
            Preferencias de Notificaciones
        </h3>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium">Notificaciones por Email</h4>
                    <p class="text-sm text-gray-600">Recibe actualizaciones importantes por correo</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium">Notificaciones de Referidos</h4>
                    <p class="text-sm text-gray-600">Notificaciones cuando alguien use tu código</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium">Recordatorios de Clicks</h4>
                    <p class="text-sm text-gray-600">Recordatorios para completar tus clicks diarios</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Zona de Peligro -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-4 flex items-center text-red-700">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Zona de Peligro
        </h3>
        
        <div class="space-y-4">
            <div>
                <h4 class="font-medium text-red-700 mb-2">Desactivar Cuenta</h4>
                <p class="text-sm text-red-600 mb-3">
                    Desactivar tu cuenta temporalmente. Podrás reactivarla cuando quieras.
                </p>
                <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                    Desactivar Cuenta
                </button>
            </div>
            
            <hr class="border-red-200">
            
            <div>
                <h4 class="font-medium text-red-700 mb-2">Eliminar Cuenta</h4>
                <p class="text-sm text-red-600 mb-3">
                    Eliminar permanentemente tu cuenta y todos tus datos. Esta acción no se puede deshacer.
                </p>
                <button class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                    Eliminar Cuenta
                </button>
            </div>
        </div>
    </div>
</div>
@endsection