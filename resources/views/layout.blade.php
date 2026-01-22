<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - PubliClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 min-h-screen p-4">
            <div class="mb-8">
                <h1 class="text-xl font-bold">PubliClick</h1>
                <p class="text-gray-400 text-sm">Panel de Control</p>
            </div>
            
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 {{ request()->routeIs('dashboard') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('anuncios') }}" class="flex items-center space-x-3 {{ request()->routeIs('anuncios') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg">
                    <i class="fas fa-mouse-pointer"></i>
                    <span>Anuncios</span>
                </a>
                <a href="{{ route('referidos') }}" class="flex items-center space-x-3 {{ request()->routeIs('referidos') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg">
                    <i class="fas fa-users"></i>
                    <span>Referidos</span>
                </a>
                <a href="{{ route('paquetes') }}" class="flex items-center space-x-3 {{ request()->routeIs('paquetes') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg">
                    <i class="fas fa-box"></i>
                    <span>Paquetes</span>
                </a>
                <a href="{{ route('billetera') }}" class="flex items-center space-x-3 {{ request()->routeIs('billetera') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg">
                    <i class="fas fa-wallet"></i>
                    <span>Billetera</span>
                </a>
                <a href="{{ route('estadisticas') }}" class="flex items-center space-x-3 {{ request()->routeIs('estadisticas') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg">
                    <i class="fas fa-chart-bar"></i>
                    <span>Estadísticas</span>
                </a>
                <a href="{{ route('configuracion') }}" class="flex items-center space-x-3 {{ request()->routeIs('configuracion') ? 'text-white bg-blue-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </nav>
            
            <div class="absolute bottom-4 left-4 right-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-red-600 p-3 rounded-lg w-full">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b p-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ Auth::user()->name ?? 'Usuario' }}</span>
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>