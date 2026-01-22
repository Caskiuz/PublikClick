<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PubliClick - Paquetes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-blue-600 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">PubliClick System</h1>
                    <p class="text-blue-200">Paquetes Publicitarios</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded">
                    Volver al Dashboard
                </a>
            </div>
        </header>

        <!-- Paquetes -->
        <div class="container mx-auto py-8 px-4">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-center mb-4">Elige tu Paquete</h2>
                <p class="text-gray-600 text-center">Selecciona el paquete que mejor se adapte a tus objetivos</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($packages as $package)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 text-center">
                            <h3 class="text-2xl font-bold">{{ $package->name }}</h3>
                            <div class="mt-4">
                                <span class="text-3xl font-bold">${{ number_format($package->price_usd, 2) }}</span>
                                <span class="text-blue-200">USD</span>
                            </div>
                            <div class="mt-2">
                                <span class="text-lg">${{ number_format($package->price_cop, 0) }}</span>
                                <span class="text-blue-200">COP</span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">{{ $package->description }}</p>
                            
                            <div class="mb-6">
                                <h4 class="font-semibold mb-2">Beneficios:</h4>
                                <ul class="space-y-2">
                                    @foreach($package->benefits as $benefit)
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm">{{ $benefit }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            <div class="text-center">
                                <button onclick="purchasePackage({{ $package->id }})" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                                    Comprar Paquete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        async function purchasePackage(packageId) {
            const currency = confirm('¿Deseas pagar en USD? (Cancelar para COP)') ? 'USD' : 'COP';
            
            try {
                const response = await fetch(`/packages/${packageId}/purchase`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        currency: currency,
                        payment_method: 'demo'
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    window.location.href = data.redirect;
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error al procesar la compra. Inténtalo de nuevo.');
            }
        }
    </script>
</body>
</html>