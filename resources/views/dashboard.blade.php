<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <title>Dashboard - PubliClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .hamburger { cursor: pointer; z-index: 60; }
        .hamburger span { display: block; width: 25px; height: 3px; background: white; margin: 5px 0; transition: all 0.3s ease; border-radius: 2px; }
        .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(8px, 8px); }
        .hamburger.active span:nth-child(2) { opacity: 0; transform: translateX(-20px); }
        .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(7px, -7px); }
        .mobile-sidebar { transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .mobile-sidebar.active { transform: translateX(0); }
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
                <a href="{{ route('dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 text-white bg-gradient-to-r from-blue-600 to-blue-500 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('anuncios') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-mouse-pointer w-5"></i>
                    <span class="font-medium">Anuncios</span>
                </a>
                <a href="{{ route('referidos') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">Referidos</span>
                </a>
                <a href="{{ route('paquetes') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-box w-5"></i>
                    <span class="font-medium">Paquetes</span>
                </a>
                <a href="{{ route('billetera') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-wallet w-5"></i>
                    <span class="font-medium">Billetera</span>
                </a>
                <a href="{{ route('estadisticas') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="font-medium">Estadísticas</span>
                </a>
                <a href="{{ route('configuracion') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg transition-all duration-200">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-medium">Configuración</span>
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
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b p-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg md:text-2xl font-semibold text-gray-800 ml-12 md:ml-0">Dashboard</h2>
                    <div class="flex items-center space-x-4">
                        <!-- Selector de Moneda -->
                        <div class="relative">
                            <button onclick="toggleCurrencyMenu()" class="flex items-center space-x-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                <i class="fas fa-globe"></i>
                                <span class="font-semibold">{{ Auth::user()->currency ?? 'COP' }}</span>
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
                                    <button onclick="changeCurrency('{{ $code }}')" class="w-full flex items-center space-x-3 px-3 py-2 hover:bg-blue-50 rounded transition-colors {{ (Auth::user()->currency ?? 'COP') === $code ? 'bg-blue-100 text-blue-600' : 'text-gray-700' }}">
                                        <span class="text-xl">{{ $info['flag'] }}</span>
                                        <div class="flex-1 text-left">
                                            <div class="font-medium">{{ $code }}</div>
                                            <div class="text-xs text-gray-500">{{ $info['name'] }}</div>
                                        </div>
                                        @if((Auth::user()->currency ?? 'COP') === $code)
                                        <i class="fas fa-check text-blue-600"></i>
                                        @endif
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-2 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                                <span class="text-gray-600 hidden md:inline">{{ Auth::user()->name ?? 'Usuario' }}</span>
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold cursor-pointer">
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

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-3 md:p-6">
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-wallet text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Balance Retiro</h3>
                                <p class="text-2xl font-bold text-green-600">{{ formatCurrency($user->withdrawalWallet->balance ?? 0) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-mouse-pointer text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Clicks Hoy</h3>
                                <p class="text-2xl font-bold text-blue-600">{{ $todayMainClicks }}/5</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-crown text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Rango</h3>
                                <p class="text-2xl font-bold text-purple-600">{{ $user->currentRank->name ?? 'Jade' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Referidos</h3>
                                <p class="text-2xl font-bold text-orange-600">{{ $user->getActiveReferralsCount() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Click System -->
                @if($user->currentPackage)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Main Ads -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <i class="fas fa-bullhorn text-blue-600 mr-2"></i>
                            Anuncios Principales
                        </h3>
                        <div class="text-center mb-4">
                            <p class="text-3xl font-bold text-blue-600">{{ 5 - $todayMainClicks }}</p>
                            <p class="text-gray-600">Clicks disponibles hoy</p>
                            <p class="text-sm text-green-600">{{ formatCurrency($user->calculateMainAdEarnings()) }} por click</p>
                        </div>
                        @if($todayMainClicks < 5 && $availableAds->count() > 0)
                            <button onclick="clickMainAd({{ $availableAds->first()->id }})" 
                                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                <i class="fas fa-mouse-pointer mr-2"></i>
                                Hacer Click Principal
                            </button>
                        @else
                            <button disabled class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed">
                                {{ $todayMainClicks >= 5 ? 'Completado por hoy' : 'Sin anuncios' }}
                            </button>
                        @endif
                    </div>

                    <!-- Mini Ads -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <i class="fas fa-star text-yellow-600 mr-2"></i>
                            Mini-Anuncios
                        </h3>
                        <div class="text-center mb-4">
                            <p class="text-3xl font-bold text-yellow-600">{{ ($user->currentRank->mini_ads_daily ?? 1) - $todayMiniClicks }}</p>
                            <p class="text-gray-600">Clicks disponibles hoy</p>
                            <p class="text-sm text-green-600">{{ formatCurrency($user->calculateMiniAdEarnings()) }} por click</p>
                        </div>
                        @if($todayMiniClicks < ($user->currentRank->mini_ads_daily ?? 1))
                            <button onclick="clickMiniAd()" 
                                    class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                <i class="fas fa-star mr-2"></i>
                                Hacer Click Mini
                            </button>
                        @else
                            <button disabled class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed">
                                Completado por hoy
                            </button>
                        @endif
                    </div>

                    <!-- Mega Ads -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <i class="fas fa-gem text-purple-600 mr-2"></i>
                            Mega-Anuncios
                        </h3>
                        <div class="text-center mb-4">
                            <p class="text-3xl font-bold text-purple-600">{{ $megaAd->clicks_remaining ?? ($user->currentRank->mega_ads_monthly ?? 10) }}</p>
                            <p class="text-gray-600">Clicks este mes</p>
                            <p class="text-sm text-green-600">{{ formatCurrency(2000) }} por click</p>
                        </div>
                        @if(!$megaAd || $megaAd->clicks_remaining > 0)
                            <button onclick="clickMegaAd()" 
                                    class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                <i class="fas fa-gem mr-2"></i>
                                Hacer Click Mega
                            </button>
                        @else
                            <button disabled class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed">
                                Agotado este mes
                            </button>
                        @endif
                    </div>
                </div>
                @else
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">
                                <strong>¡Compra un paquete para comenzar!</strong>
                                Necesitas un paquete activo para hacer clicks y ganar dinero.
                                <a href="{{ route('paquetes') }}" class="underline font-semibold">Ver paquetes disponibles</a>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- User Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Información de Usuario
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-gray-600">Paquete Actual</p>
                            <p class="font-semibold">{{ $user->currentPackage->name ?? 'Sin paquete' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Rango</p>
                            <p class="font-semibold text-purple-600">{{ $user->currentRank->name ?? 'Jade' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Código de Referido</p>
                            <p class="font-semibold text-blue-600">{{ $user->referral_code ?? 'No asignado' }}</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Notificaciones -->
    <div id="notificationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6 transform transition-all">
            <div class="text-center">
                <div id="notificationIcon" class="mx-auto mb-4"></div>
                <h3 id="notificationTitle" class="text-xl font-bold mb-2"></h3>
                <p id="notificationMessage" class="text-gray-600 mb-6"></p>
                <button onclick="closeNotification()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg">
                    Aceptar
                </button>
            </div>
        </div>
    </div>

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
        
        async function changeCurrency(currency) {
            try {
                const response = await fetch('/currency/change', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ currency: currency })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Moneda actualizada!',
                        text: `Ahora verás los valores en ${currency}`,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cambiar la moneda'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al cambiar la moneda'
                });
            }
        }
        
        function showNotification(title, message, type = 'success') {
            const modal = document.getElementById('notificationModal');
            const icon = document.getElementById('notificationIcon');
            const titleEl = document.getElementById('notificationTitle');
            const messageEl = document.getElementById('notificationMessage');
            
            const icons = {
                success: '<div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center"><i class="fas fa-check text-green-500 text-3xl"></i></div>',
                error: '<div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center"><i class="fas fa-times text-red-500 text-3xl"></i></div>',
                warning: '<div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center"><i class="fas fa-exclamation text-yellow-500 text-3xl"></i></div>',
                info: '<div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center"><i class="fas fa-info text-blue-500 text-3xl"></i></div>'
            };
            
            icon.innerHTML = icons[type] || icons.success;
            titleEl.textContent = title;
            messageEl.textContent = message;
            modal.classList.remove('hidden');
        }

        function closeNotification() {
            document.getElementById('notificationModal').classList.add('hidden');
        }

        async function clickMainAd(adId) {
            try {
                const response = await fetch(`/clicks/main/${adId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Ganaste!',
                        html: `Ganaste <strong>${data.earnings}</strong><br>Clicks restantes: ${data.remaining_clicks}`,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar el click'
                });
            }
        }

        async function clickMiniAd() {
            try {
                const response = await fetch('/clicks/mini', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Ganaste!',
                        html: `Ganaste <strong>${data.earnings}</strong><br>Mini-clicks restantes: ${data.remaining_mini_clicks}`,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar el click'
                });
            }
        }

        async function clickMegaAd() {
            try {
                const response = await fetch('/clicks/mega', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡MEGA-CLICK!',
                        html: `Ganaste <strong>${data.earnings}</strong><br>Mega-clicks restantes: ${data.remaining_mega_clicks}`,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar el click'
                });
            }
        }
    </script>
</body>
</html>