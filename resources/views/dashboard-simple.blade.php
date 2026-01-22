<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PubliClick</title>
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
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-white bg-blue-600 p-3 rounded-lg">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('anuncios') }}" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg">
                    <i class="fas fa-mouse-pointer"></i>
                    <span>Anuncios</span>
                </a>
                <a href="{{ route('referidos') }}" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg">
                    <i class="fas fa-users"></i>
                    <span>Referidos</span>
                </a>
                <a href="{{ route('paquetes') }}" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg">
                    <i class="fas fa-box"></i>
                    <span>Paquetes</span>
                </a>
                <a href="{{ route('billetera') }}" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg">
                    <i class="fas fa-wallet"></i>
                    <span>Billetera</span>
                </a>
                <a href="{{ route('estadisticas') }}" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg">
                    <i class="fas fa-chart-bar"></i>
                    <span>Estadísticas</span>
                </a>
                <a href="{{ route('configuracion') }}" class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-3 rounded-lg">
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
                    <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ Auth::user()->name ?? 'Usuario' }}</span>
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-wallet text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Balance Retiro</h3>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($user->wallet_balance ?? 0, 2) }}</p>
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
                            <p class="text-sm text-green-600">${{ number_format($user->calculateMainAdEarnings(), 2) }} por click</p>
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
                            <p class="text-sm text-green-600">${{ number_format($user->calculateMiniAdEarnings(), 2) }} por click</p>
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
                            <p class="text-sm text-green-600">$2,000 por click</p>
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
                    showNotification('¡Ganaste!', `Ganaste $${data.earnings}! Clicks restantes: ${data.remaining_clicks}`, 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showNotification('Error', data.message, 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error al procesar el click', 'error');
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
                    showNotification('¡Ganaste!', `Ganaste $${data.earnings}! Mini-clicks restantes: ${data.remaining_mini_clicks}`, 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showNotification('Error', data.message, 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error al procesar el click', 'error');
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
                    showNotification('¡MEGA-CLICK!', `Ganaste $${data.earnings}! Mega-clicks restantes: ${data.remaining_mega_clicks}`, 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showNotification('Error', data.message, 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error al procesar el click', 'error');
            }
        }
    </script>
</body>
</html>