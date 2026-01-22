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
            <header class="bg-white shadow-sm border-b">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Documentación -->
                        <a href="#" onclick="Modal.info('Documentación disponible próximamente', 'Documentación')" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-book"></i>
                            <span class="hidden md:inline">Docs</span>
                        </a>
                        
                        <!-- Selector de Moneda -->
                        <div class="relative">
                            <button onclick="toggleCurrencyMenu()" class="flex items-center space-x-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                <i class="fas fa-globe"></i>
                                <span class="font-semibold">{{ Auth::user()->currency ?? 'USD' }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div id="currencyMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border z-50">
                                <div class="p-2">
                                    <div class="text-xs text-gray-500 uppercase font-semibold px-3 py-2">Seleccionar Moneda</div>
                                    @php
                                        $currencies = [
                                            'USD' => ['name' => 'Dólar', 'flag' => '🇺🇸'],
                                            'COP' => ['name' => 'Peso Colombiano', 'flag' => '🇨🇴'],
                                            'EUR' => ['name' => 'Euro', 'flag' => '🇪🇺'],
                                            'MXN' => ['name' => 'Peso Mexicano', 'flag' => '🇲🇽'],
                                            'ARS' => ['name' => 'Peso Argentino', 'flag' => '🇦🇷'],
                                            'BRL' => ['name' => 'Real Brasileño', 'flag' => '🇧🇷'],
                                            'CLP' => ['name' => 'Peso Chileno', 'flag' => '🇨🇱'],
                                            'PEN' => ['name' => 'Sol Peruano', 'flag' => '🇵🇪'],
                                        ];
                                    @endphp
                                    @foreach($currencies as $code => $info)
                                    <button onclick="changeCurrency('{{ $code }}')" class="w-full flex items-center space-x-3 px-3 py-2 hover:bg-blue-50 rounded transition-colors {{ (Auth::user()->currency ?? 'USD') === $code ? 'bg-blue-100 text-blue-600' : 'text-gray-700' }}">
                                        <span class="text-xl">{{ $info['flag'] }}</span>
                                        <div class="flex-1 text-left">
                                            <div class="font-medium">{{ $code }}</div>
                                            <div class="text-xs text-gray-500">{{ $info['name'] }}</div>
                                        </div>
                                        @if((Auth::user()->currency ?? 'USD') === $code)
                                        <i class="fas fa-check text-blue-600"></i>
                                        @endif
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Usuario -->
                        <div class="flex items-center space-x-3">
                            <div class="text-right hidden md:block">
                                <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Usuario' }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->currentRank->name ?? 'Sin rango' }}</div>
                            </div>
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
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
    
    @include('components.modal')
    
    <script>
        function toggleCurrencyMenu() {
            const menu = document.getElementById('currencyMenu');
            menu.classList.toggle('hidden');
        }
        
        // Cerrar menú al hacer click fuera
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('currencyMenu');
            const button = event.target.closest('button[onclick="toggleCurrencyMenu()"]');
            
            if (!button && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
        
        async function changeCurrency(currency) {
            try {
                const response = await fetch('/currency/change', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ currency: currency })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    Modal.error('Error al cambiar moneda', 'Error');
                }
            } catch (error) {
                Modal.error('Error al cambiar moneda', 'Error');
            }
        }
    </script>
</body>
</html>