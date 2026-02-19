<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold">👥 Gestión de Usuarios</h1>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <nav class="flex gap-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600 pb-2">Dashboard</a>
                    <a href="{{ route('admin.withdrawals') }}" class="text-gray-600 hover:text-purple-600 pb-2">Retiros</a>
                    <a href="{{ route('admin.users') }}" class="text-purple-600 font-semibold border-b-2 border-purple-600 pb-2">Usuarios</a>
                    <a href="{{ route('admin.reports') }}" class="text-gray-600 hover:text-purple-600 pb-2">Reportes</a>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto p-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Total Usuarios</p>
                    <p class="text-2xl font-bold">{{ $users->total() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Con Paquete Activo</p>
                    <p class="text-2xl font-bold text-green-600">{{ $users->where('current_package_id', '!=', null)->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Sin Paquete</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $users->where('current_package_id', null)->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Administradores</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $users->where('is_admin', true)->count() }}</p>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paquete</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rango</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saldo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estrellas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->country }} - {{ $user->city }}</p>
                                        @if($user->is_admin)
                                            <span class="inline-block bg-purple-500 text-white text-xs px-2 py-1 rounded mt-1">👑 Admin</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->currentPackage)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                            ${{ $user->currentPackage->price_usd }} USD
                                        </span>
                                        @if($user->package_purchased_at)
                                            <p class="text-xs text-gray-500 mt-1">{{ $user->package_purchased_at->format('d/m/Y') }}</p>
                                        @endif
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm">Sin paquete</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->currentRank)
                                        <span class="font-semibold text-purple-600">{{ $user->currentRank->name }}</span>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->withdrawalWallet)
                                        <p class="font-bold text-green-600">${{ number_format($user->withdrawalWallet->balance, 0) }}</p>
                                        <p class="text-xs text-gray-500">COP</p>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-2xl">{{ str_repeat('⭐', $user->stars ?? 0) }}</span>
                                    <p class="text-xs text-gray-500">{{ $user->stars ?? 0 }}/5</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_active)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">✅ Activo</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">❌ Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</body>
</html>
