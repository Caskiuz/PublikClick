<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Dashboard') - PubliClick</title>
    <script src="https://cdn.tailwindcss.com?v={{ time() }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { overscroll-behavior: none; }
        .hamburger { cursor: pointer; z-index: 60; }
        .hamburger span { display: block; width: 25px; height: 3px; background: white; margin: 5px 0; transition: all 0.3s ease; border-radius: 2px; }
        .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(8px, 8px); }
        .hamburger.active span:nth-child(2) { opacity: 0; transform: translateX(-20px); }
        .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(7px, -7px); }
        .mobile-sidebar { transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); will-change: transform; }
        .mobile-sidebar.active { transform: translateX(0); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3); }
        @media (min-width: 768px) {
            .mobile-sidebar { transform: translateX(0) !important; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Mobile Menu Button -->
    <button onclick="toggleMobileSidebar()" id="hamburger" class="hamburger md:hidden fixed top-4 left-4 z-50 bg-gray-800 p-3 rounded-lg shadow-lg">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" onclick="toggleMobileSidebar()" class="hidden md:hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="mobile-sidebar fixed md:relative bg-gray-800 text-white w-72 min-h-screen p-4 z-40 shadow-2xl">
            <div class="mb-8 mt-2">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">PubliClick</h1>
                <p class="text-gray-400 text-sm">Panel de Control</p>
            </div>
            
            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('dashboard') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Inicio</span>
                </a>
                
                <!-- Mis Tareas (Dropdown) -->
                <div x-data="{ open: {{ request()->routeIs('anuncios*') || request()->routeIs('mini-anuncios') || request()->routeIs('mega-anuncios') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-tasks w-5"></i>
                            <span class="font-medium">Mis Tareas</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                        <a href="{{ route('anuncios-principales') }}" onclick="closeMobileSidebar()" class="block text-gray-400 hover:text-white p-2 rounded">
                            Anuncios Principales
                        </a>
                        <a href="{{ route('mini-anuncios') }}" onclick="closeMobileSidebar()" class="block text-gray-400 hover:text-white p-2 rounded">
                            Tareas diarias mini anuncios
                        </a>
                        <a href="{{ route('mega-anuncios') }}" onclick="closeMobileSidebar()" class="block text-gray-400 hover:text-white p-2 rounded">
                            Mega anuncios
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('tablero') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('tablero') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-chart-line w-5"></i>
                    <span class="font-medium">Tablero</span>
                </a>
                
                <a href="{{ route('referidos') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('referidos') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">Tus Invitados</span>
                </a>
                
                <a href="{{ route('lider') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('lider') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-user-tie w-5"></i>
                    <span class="font-medium">Líder</span>
                </a>
                
                <a href="{{ route('calculadora') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('calculadora') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-calculator w-5"></i>
                    <span class="font-medium">Calculadora de Ganancias</span>
                </a>
                
                <a href="{{ route('historial-retiros') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('historial-retiros') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-history w-5"></i>
                    <span class="font-medium">Historial de Retiros</span>
                </a>
                
                <!-- Comunidad (Dropdown) -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-users-cog w-5"></i>
                            <span class="font-medium">Comunidad</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                        <a href="{{ route('amigos') }}" onclick="closeMobileSidebar()" class="block text-gray-400 hover:text-white p-2 rounded">
                            Amigos
                        </a>
                        <a href="{{ route('testimonios') }}" onclick="closeMobileSidebar()" class="block text-gray-400 hover:text-white p-2 rounded">
                            Testimonios
                        </a>
                        <a href="{{ route('proyectos-donaciones') }}" onclick="closeMobileSidebar()" class="block text-gray-400 hover:text-white p-2 rounded">
                            Proyectos Donaciones
                        </a>
                        <a href="{{ route('sube-proyecto') }}" onclick="closeMobileSidebar()" class="block text-gray-400 hover:text-white p-2 rounded">
                            Sube tu Proyecto
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('paquetes') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('paquetes') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-box w-5"></i>
                    <span class="font-medium">Paquetes Publicitarios</span>
                </a>
                
                <a href="{{ route('mis-paquetes') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('mis-paquetes') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-boxes w-5"></i>
                    <span class="font-medium">Mis Paquetes</span>
                </a>
                
                <!-- Administración de Publicaciones (Dropdown) -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-ad w-5"></i>
                            <span class="font-medium">Admin. Publicaciones</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                        <a href="{{ route('crear-ptc') }}" onclick="closeMobileSidebar()" class="block text-gray-400 hover:text-white p-2 rounded">
                            Crea tu Publicidad PTC
                        </a>
                        <a href="{{ route('crear-banner') }}" onclick="closeMobileSidebar()" class="block text-gray-400 hover:text-white p-2 rounded">
                            Crea tu Publicidad Banner
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('recomienda-gana') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('recomienda-gana') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-share-alt w-5"></i>
                    <span class="font-medium">Recomienda y Gana</span>
                </a>
                
                <a href="{{ route('mundo-sorteos') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('mundo-sorteos') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-gift w-5"></i>
                    <span class="font-medium">Mundo Sorteos</span>
                </a>
                
                <a href="{{ route('billetera') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('billetera') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-wallet w-5"></i>
                    <span class="font-medium">Billetera</span>
                </a>
                
                <a href="{{ route('estadisticas') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('estadisticas') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="font-medium">Estadísticas</span>
                </a>
                
                <a href="{{ route('perfil') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('perfil') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-user-circle w-5"></i>
                    <span class="font-medium">Mi Perfil</span>
                </a>
                
                @if(Auth::user()->is_admin)
                <a href="{{ route('configuracion') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 {{ request()->routeIs('configuracion') ? 'text-white bg-gradient-to-r from-purple-600 to-purple-500' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} p-3 rounded-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-medium">Configuración</span>
                </a>
                @endif
            </nav>
            
            <div class="absolute bottom-4 left-4 right-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gradient-to-r hover:from-red-600 hover:to-red-500 p-3 rounded-lg w-full transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="font-medium">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex justify-between items-center px-4 md:px-6 py-3 md:py-4">
                    <h2 class="text-lg md:text-2xl font-semibold text-gray-800 ml-12 md:ml-0">@yield('page-title', 'Dashboard')</h2>
                    
                    <div class="flex items-center space-x-2 md:space-x-6">
                        <!-- Selector de Moneda -->
                        <div class="relative">
                            <button onclick="toggleCurrencyMenu()" class="flex items-center space-x-1 md:space-x-2 px-2 md:px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors text-sm md:text-base">
                                <i class="fas fa-globe text-sm"></i>
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
                        <div class="relative">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-2 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                                <div class="text-right hidden lg:block">
                                    <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Usuario' }}</div>
                                    <div class="text-xs text-gray-500">{{ Auth::user()->currentRank->name ?? 'Sin rango' }}</div>
                                </div>
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg text-sm md:text-base cursor-pointer">
                                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                </div>
                            </button>
                            
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                                <div class="p-2">
                                    <a href="{{ route('perfil') }}" class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-700">
                                        <i class="fas fa-user w-5"></i>
                                        <span>Mi Perfil</span>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2 hover:bg-red-50 rounded-lg transition-colors text-red-600">
                                            <i class="fas fa-sign-out-alt w-5"></i>
                                            <span>Cerrar Sesión</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-3 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    @include('components.modal')
    
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const hamburger = document.getElementById('hamburger');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('hidden');
            hamburger.classList.toggle('active');
        }

        function closeMobileSidebar() {
            if (window.innerWidth < 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobileOverlay');
                const hamburger = document.getElementById('hamburger');
                
                sidebar.classList.remove('active');
                overlay.classList.add('hidden');
                hamburger.classList.remove('active');
            }
        }

        function toggleCurrencyMenu() {
            const menu = document.getElementById('currencyMenu');
            menu.classList.toggle('hidden');
        }
        
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }
        
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('currencyMenu');
            const userMenu = document.getElementById('userMenu');
            const currencyButton = event.target.closest('button[onclick="toggleCurrencyMenu()"]');
            const userButton = event.target.closest('button[onclick="toggleUserMenu()"]');
            
            if (!currencyButton && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
            
            if (!userButton && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
        
        function changeCurrency(currency) {
            document.getElementById('currencyMenu').classList.add('hidden');
            
            fetch('{{ route("currency.change") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ currency: currency })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Listo!',
                        text: 'Moneda actualizada',
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cambiar la moneda'
                });
            });
        }

        // Cerrar sidebar al redimensionar ventana
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                closeMobileSidebar();
            }
        });
    </script>
</body>
</html>