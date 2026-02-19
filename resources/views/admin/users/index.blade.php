@extends('layout')

@section('title', 'Gestión de Usuarios')
@section('page-title', 'Gestión de Usuarios')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-users mr-2 text-blue-600"></i>
            Gestión de Usuarios
        </h2>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Usuarios</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['total'] }}</h3>
                </div>
                <i class="fas fa-users text-4xl opacity-30"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Activos</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['active'] }}</h3>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-30"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Inactivos</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['inactive'] }}</h3>
                </div>
                <i class="fas fa-ban text-4xl opacity-30"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Admins</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['admins'] }}</h3>
                </div>
                <i class="fas fa-shield-alt text-4xl opacity-30"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, email, código..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-search mr-2"></i>Buscar
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paquete</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rango</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Referidos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $u->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $u->email }}</div>
                                        <div class="text-xs text-gray-400">Ref: {{ $u->referral_code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($u->currentPackage)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        ${{ $u->currentPackage->price_usd }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Sin paquete</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($u->currentRank)
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold" style="background-color: {{ $u->currentRank->color }}20; color: {{ $u->currentRank->color }}">
                                        {{ $u->currentRank->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Sin rango</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                {{ formatCurrency($u->wallet_balance, 'COP') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $u->active_referrals_count ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $u->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($u->is_active)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Activo
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-ban mr-1"></i> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button onclick="toggleStatus({{ $u->id }}, {{ $u->is_active ? 'false' : 'true' }})" 
                                        class="text-{{ $u->is_active ? 'red' : 'green' }}-600 hover:text-{{ $u->is_active ? 'red' : 'green' }}-900">
                                    <i class="fas fa-{{ $u->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-2 opacity-30"></i>
                                <p>No hay usuarios registrados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
function toggleStatus(userId, newStatus) {
    Swal.fire({
        title: newStatus ? '¿Activar usuario?' : '¿Desactivar usuario?',
        text: newStatus ? 'El usuario podrá acceder al sistema' : 'El usuario no podrá acceder al sistema',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    });
}
</script>
@endsection
