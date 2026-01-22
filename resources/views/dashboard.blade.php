<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PubliClick - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-blue-600 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">PubliClick System</h1>
                    <p class="text-blue-200">Sistema de Fidelización "Recomienda y Gana"</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-blue-200">Hola, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-700 px-4 py-2 rounded">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Dashboard -->
        <div class="container mx-auto py-8 px-4">
            <!-- Navegación -->
            <div class="mb-8">
                <nav class="flex space-x-4">
                    <a href="{{ route('dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Dashboard</a>
                    <a href="{{ route('packages.index') }}" class="bg-purple-500 text-white px-4 py-2 rounded">Paquetes</a>
                    <a href="{{ route('referrals.index') }}" class="bg-green-500 text-white px-4 py-2 rounded">Referidos</a>
                </nav>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Balance</h3>
                    <p class="text-3xl font-bold text-green-600">${{ number_format($stats['wallet_balance'], 2) }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Clicks Hoy</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_clicks_today'] }}/5</p>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Referidos</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['total_referrals'] }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Ganancias Totales</h3>
                    <p class="text-3xl font-bold text-orange-600">${{ number_format($stats['total_earnings'], 2) }}</p>
                </div>
            </div>

            <!-- Anuncios Disponibles -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4">Anuncios Disponibles Hoy</h3>
                    
                    @if(count($availableAds) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($availableAds as $ad)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <h4 class="font-semibold">{{ $ad['title'] }}</h4>
                                    <p class="text-gray-600 text-sm mb-3">{{ $ad['description'] }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-green-600 font-bold">${{ number_format($ad['click_value'], 2) }}</span>
                                        <button onclick="clickAd({{ $ad['id'] }})" 
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            Hacer Click
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No hay anuncios disponibles o ya completaste tus 5 clicks diarios.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        async function clickAd(adId) {
            try {
                const response = await fetch(`/ads/${adId}/click`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Mostrar mensaje de éxito
                    alert(data.message);
                    
                    // Recargar la página para actualizar estadísticas
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error al procesar el click. Inténtalo de nuevo.');
            }
        }
    </script>
</body>
</html>