<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PubliHazClick - La red social que te paga por ver anuncios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    <!-- Billetera Simulada Flotante -->
    <div class="fixed top-4 right-4 z-50 bg-white rounded-lg shadow-xl p-4 max-w-sm">
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-2">Este sería el dinero que podrías retirar si te conviertes en anunciante</p>
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-4">
                <i class="fas fa-wallet text-2xl mb-2"></i>
                <p class="text-sm font-semibold">Acumulado de Retiro</p>
                <p class="text-3xl font-bold" id="simulatedBalance">$0</p>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-3 mt-2">
                <p class="text-xs font-semibold">Donaciones</p>
                <p class="text-xl font-bold" id="simulatedDonations">$0</p>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-6">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">PubliHazClick</h1>
                    <p class="text-sm">La red social que te paga por ver anuncios</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Sección de Anuncios de Demostración -->
    <section class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-center mb-6">Prueba cómo funciona - Haz click en los anuncios</h2>
            
            <!-- Mega Anuncios -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    Mega Anuncios - $2,000 COP cada uno
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="border-2 border-yellow-400 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('mega', 2000)">
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-lg p-6 text-center">
                            <i class="fas fa-ad text-4xl mb-2"></i>
                            <p class="font-bold">Mega Anuncio {{ $i }}</p>
                            <p class="text-sm">Click para ganar $2,000</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Anuncios Principales -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-bullhorn text-blue-500 mr-2"></i>
                    Anuncios Principales - $400 / $600 COP cada uno
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="border-2 border-blue-400 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('main', 400)">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white rounded-lg p-4 text-center">
                            <i class="fas fa-mouse-pointer text-3xl mb-2"></i>
                            <p class="font-bold">Anuncio $400</p>
                            <p class="text-xs">90 segundos</p>
                        </div>
                    </div>
                    <div class="border-2 border-blue-400 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('main', 600)">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg p-4 text-center">
                            <i class="fas fa-mouse-pointer text-3xl mb-2"></i>
                            <p class="font-bold">Anuncio $600</p>
                            <p class="text-xs">90 segundos</p>
                        </div>
                    </div>
                    <div class="border-2 border-blue-400 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('main', 400)">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white rounded-lg p-4 text-center">
                            <i class="fas fa-mouse-pointer text-3xl mb-2"></i>
                            <p class="font-bold">Anuncio $400</p>
                            <p class="text-xs">90 segundos</p>
                        </div>
                    </div>
                    <div class="border-2 border-blue-400 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('main', 600)">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg p-4 text-center">
                            <i class="fas fa-mouse-pointer text-3xl mb-2"></i>
                            <p class="font-bold">Anuncio $600</p>
                            <p class="text-xs">90 segundos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mini Anuncios -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-coins text-green-500 mr-2"></i>
                    Mini Anuncios - $100 COP cada uno
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @for($i = 1; $i <= 6; $i++)
                    <div class="border-2 border-green-400 rounded-lg p-3 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('mini', 100)">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 text-white rounded-lg p-3 text-center">
                            <i class="fas fa-hand-pointer text-2xl mb-1"></i>
                            <p class="text-xs font-bold">Mini {{ $i }}</p>
                            <p class="text-xs">$100</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Sección "Así de fácil es ganar con nosotros" -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow-xl p-8 mb-8 text-center">
            <h2 class="text-3xl font-bold mb-4">¡Así de fácil es ganar con nosotros!</h2>
            <p class="text-xl">Haz click, gana dinero y refiere amigos</p>
        </div>

        <!-- Video Explicativo -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-center mb-6">¿Quieres ver cómo funciona?</h2>
            <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center mb-6">
                <div class="text-center">
                    <i class="fas fa-play-circle text-6xl text-blue-600 mb-4"></i>
                    <p class="text-gray-600">Video explicativo del modelo de negocio</p>
                    <p class="text-sm text-gray-500">(Aquí irá tu video de YouTube/Vimeo)</p>
                </div>
            </div>
            
            <!-- Botón de Registro -->
            <div class="text-center">
                <a href="{{ route('register') }}{{ request()->has('ref') ? '?ref=' . request()->get('ref') : '' }}" 
                   class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-lg text-xl font-bold hover:shadow-xl transition transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>
                    Regístrate en PubliHazClick
                </a>
                <p class="text-sm text-gray-500 mt-4">
                    @if(request()->has('ref'))
                        Serás referido por: <span class="font-bold text-blue-600">{{ request()->get('ref') }}</span>
                    @else
                        Necesitas un link de referido para registrarte
                    @endif
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2026 PubliHazClick. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        let simulatedBalance = 0;
        let simulatedDonations = 0;

        function simulateClick(type, amount) {
            // Para anuncios principales, $10 van a donaciones
            if (type === 'main') {
                simulatedDonations += 10;
                simulatedBalance += (amount - 10);
            } else {
                simulatedBalance += amount;
            }

            // Actualizar display
            document.getElementById('simulatedBalance').textContent = '$' + simulatedBalance.toLocaleString('es-CO');
            document.getElementById('simulatedDonations').textContent = '$' + simulatedDonations.toLocaleString('es-CO');

            // Mostrar mensaje
            Swal.fire({
                title: '¡Excelente!',
                html: `<p class="text-lg">Esto es lo que podrías ganar por ver este anuncio si te conviertes en anunciante</p>
                       <p class="text-3xl font-bold text-green-600 mt-4">+$${amount.toLocaleString('es-CO')} COP</p>`,
                icon: 'success',
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#3B82F6'
            });
        }
    </script>
</body>
</html>
