<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - PubliHazClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-6">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <h1 class="text-3xl font-bold">🎛️ Panel Administrativo</h1>
                <div class="flex gap-4">
                    <a href="{{ route('dashboard') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100">
                        🏠 Volver al sitio
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 px-4 py-2 rounded-lg font-semibold hover:bg-red-600">
                            🚪 Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <nav class="flex gap-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-purple-600 font-semibold border-b-2 border-purple-600 pb-2">Dashboard</a>
                    <a href="{{ route('admin.withdrawals') }}" class="text-gray-600 hover:text-purple-600 pb-2">Retiros</a>
                    <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-purple-600 pb-2">Usuarios</a>
                    <a href="{{ route('admin.reports') }}" class="text-gray-600 hover:text-purple-600 pb-2">Reportes</a>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Usuarios</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</p>
                            <p class="text-green-600 text-sm mt-1">{{ number_format($stats['active_users']) }} activos</p>
                        </div>
                        <div class="text-5xl">👥</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Clicks Hoy</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_clicks_today']) }}</p>
                            <p class="text-blue-600 text-sm mt-1">Anuncios vistos</p>
                        </div>
                        <div class="text-5xl">🖱️</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Retiros Pendientes</p>
                            <p class="text-3xl font-bold text-orange-600">{{ number_format($stats['pending_withdrawals']) }}</p>
                            <p class="text-gray-600 text-sm mt-1">Requieren atención</p>
                        </div>
                        <div class="text-5xl">⏳</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Pagado</p>
                            <p class="text-3xl font-bold text-green-600">${{ number_format($stats['total_earnings_paid'], 0) }}</p>
                            <p class="text-gray-600 text-sm mt-1">COP en retiros</p>
                        </div>
                        <div class="text-5xl">💰</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Ingresos Totales</p>
                            <p class="text-3xl font-bold text-purple-600">${{ number_format($stats['total_revenue'], 0) }}</p>
                            <p class="text-gray-600 text-sm mt-1">USD en paquetes</p>
                        </div>
                        <div class="text-5xl">📈</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pending Withdrawals -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">⏳ Retiros Pendientes</h2>
                    <div class="space-y-3">
                        @forelse($pending_withdrawals as $withdrawal)
                            <div class="border-l-4 border-orange-500 bg-orange-50 p-3 rounded">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $withdrawal->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $withdrawal->user->email }}</p>
                                        <p class="text-lg font-bold text-orange-600 mt-1">${{ number_format($withdrawal->amount, 0) }} COP</p>
                                    </div>
                                    <a href="{{ route('admin.withdrawals') }}" class="bg-orange-500 text-white px-3 py-1 rounded text-sm hover:bg-orange-600">
                                        Ver
                                    </a>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">{{ $withdrawal->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No hay retiros pendientes</p>
                        @endforelse
                    </div>
                    <a href="{{ route('admin.withdrawals') }}" class="block text-center text-purple-600 font-semibold mt-4 hover:underline">
                        Ver todos los retiros →
                    </a>
                </div>

                <!-- Recent Users -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">👥 Usuarios Recientes</h2>
                    <div class="space-y-3">
                        @foreach($recent_users as $user)
                            <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        @if($user->currentPackage)
                                            <span class="inline-block bg-green-500 text-white text-xs px-2 py-1 rounded mt-1">
                                                Paquete ${{ $user->currentPackage->price_usd }}
                                            </span>
                                        @else
                                            <span class="inline-block bg-gray-400 text-white text-xs px-2 py-1 rounded mt-1">
                                                Sin paquete
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Registrado {{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
