<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PubliHazClick - Gana Dinero Viendo Anuncios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background-color: #000000; }
        .gradient-primary { background: linear-gradient(135deg, #00FFFF 0%, #03E9F4 100%); }
        .gradient-success { background: linear-gradient(135deg, #4ECDC4 0%, #44A08D 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,255,255,0.3); }
        .btn-primary { background: linear-gradient(135deg, #00FFFF 0%, #03E9F4 100%); color: #000000; }
        .btn-primary:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(0,255,255,0.5); }
        .text-primary { color: #00FFFF; }
        .text-secondary { color: #FFFFFF; }
        .bg-dark { background-color: #000000; }
        .border-primary { border-color: #00FFFF; }
    </style>
</head>
<body class="bg-black">
    <!-- Navigation -->
    <nav class="bg-black border-b border-gray-800 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 gradient-primary rounded-lg flex items-center justify-center">
                        <i class="fas fa-mouse-pointer text-black text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-primary">PubliHazClick</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#inicio" class="text-white hover:text-primary font-medium transition">Inicio</a>
                    <a href="#como-funciona" class="text-white hover:text-primary font-medium transition">Cómo Funciona</a>
                    <a href="#paquetes" class="text-white hover:text-primary font-medium transition">Paquetes</a>
                    <a href="#testimonios" class="text-white hover:text-primary font-medium transition">Testimonios</a>
                    <button onclick="window.location.href='/login'" class="btn-primary px-6 py-2.5 rounded-lg font-semibold transition">
                        Iniciar Sesión
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="pt-20 relative">
        <div class="relative overflow-hidden bg-black">
            <!-- Banner Slider -->
            <div class="banner-slider relative">
                <img src="{{ asset('publiclikfiles/WEB BANNER 1.png') }}" alt="Banner 1" class="banner-slide w-full h-full object-contain active">
                <img src="{{ asset('publiclikfiles/WEB BANNER 4.png') }}" alt="Banner 4" class="banner-slide w-full h-full object-contain">
                <img src="{{ asset('publiclikfiles/WEB BANNER 8.png') }}" alt="Banner 8" class="banner-slide w-full h-full object-contain">
                <img src="{{ asset('publiclikfiles/WEB BANNER 9.png') }}" alt="Banner 9" class="banner-slide w-full h-full object-contain">
                <img src="{{ asset('publiclikfiles/WEB BANNER 12.png') }}" alt="Banner 12" class="banner-slide w-full h-full object-contain">
            </div>
        </div>
        <!-- Botón debajo del banner -->
        <div class="text-center py-3 md:py-4 bg-black">
            <button onclick="window.location.href='/register'" class="btn-primary px-6 py-2.5 md:px-10 md:py-4 rounded-full text-sm md:text-lg font-bold transition transform hover:scale-105 shadow-2xl">
                <i class="fas fa-rocket mr-2"></i>Comenzar Ahora
            </button>
        </div>
    </section>

    <!-- Demo Anuncios Simulados -->
    <section class="py-12 md:py-20 bg-gradient-to-b from-black to-gray-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <h2 class="text-3xl md:text-5xl font-bold text-primary mb-4">💰 Esto es lo que podrías ganar...</h2>
                <p class="text-lg md:text-xl text-white">Haz click en los anuncios y mira cómo crece tu billetera</p>
            </div>

            <!-- Billetera Flotante -->
            <div id="demo-wallet" class="fixed top-24 right-4 md:right-8 z-40 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-2xl p-4 md:p-6 border-2 border-green-400 transform transition-all duration-300">
                <div class="text-center">
                    <div class="text-white text-xs md:text-sm font-semibold mb-2">💰 Tu Billetera Demo</div>
                    <div id="demo-balance" class="text-2xl md:text-4xl font-bold text-white mb-1">$0</div>
                    <div class="text-green-100 text-xs">COP</div>
                    <div class="mt-3 text-xs text-green-100">
                        <div>Clicks: <span id="demo-clicks" class="font-bold">0</span></div>
                    </div>
                </div>
            </div>

            <!-- Anuncios Demo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <!-- Mega Anuncio Demo -->
                <div class="bg-gray-800 border-2 border-yellow-500 rounded-2xl p-6 card-hover relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-yellow-500 text-black px-3 py-1 rounded-bl-lg text-xs font-bold">
                        MEGA
                    </div>
                    <div class="text-center mb-4">
                        <i class="fas fa-star text-5xl text-yellow-500 mb-3"></i>
                        <h3 class="text-xl font-bold text-white mb-2">Mega Anuncio</h3>
                        <div class="text-3xl font-bold text-yellow-500">$2,000</div>
                        <div class="text-gray-400 text-sm">COP por click</div>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4 mb-4 min-h-[120px] flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <i class="fas fa-ad text-4xl mb-2"></i>
                            <p class="text-sm">Anuncio de 120 segundos</p>
                        </div>
                    </div>
                    <button onclick="simulateClick(2000, 'mega')" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-black font-bold py-3 rounded-lg hover:from-yellow-600 hover:to-orange-600 transition transform hover:scale-105">
                        <i class="fas fa-mouse-pointer mr-2"></i>Hacer Click Demo
                    </button>
                    <div class="mt-3 text-center text-xs text-gray-400">
                        ⏱️ 120 segundos • 💎 Acumulable 30 días
                    </div>
                </div>

                <!-- Anuncio Principal Demo -->
                <div class="bg-gray-800 border-2 border-cyan-500 rounded-2xl p-6 card-hover relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-cyan-500 text-black px-3 py-1 rounded-bl-lg text-xs font-bold">
                        PRINCIPAL
                    </div>
                    <div class="text-center mb-4">
                        <i class="fas fa-bullhorn text-5xl text-cyan-500 mb-3"></i>
                        <h3 class="text-xl font-bold text-white mb-2">Anuncio Principal</h3>
                        <div class="text-3xl font-bold text-cyan-500">$400-$600</div>
                        <div class="text-gray-400 text-sm">COP por click</div>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4 mb-4 min-h-[120px] flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <i class="fas fa-ad text-4xl mb-2"></i>
                            <p class="text-sm">Anuncio de 90 segundos</p>
                        </div>
                    </div>
                    <button onclick="simulateClick(500, 'principal')" class="w-full bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-bold py-3 rounded-lg hover:from-cyan-600 hover:to-blue-600 transition transform hover:scale-105">
                        <i class="fas fa-mouse-pointer mr-2"></i>Hacer Click Demo
                    </button>
                    <div class="mt-3 text-center text-xs text-gray-400">
                        ⏱️ 90 segundos • 🔄 5 clicks diarios
                    </div>
                </div>

                <!-- Mini Anuncio Demo -->
                <div class="bg-gray-800 border-2 border-green-500 rounded-2xl p-6 card-hover relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-green-500 text-black px-3 py-1 rounded-bl-lg text-xs font-bold">
                        MINI
                    </div>
                    <div class="text-center mb-4">
                        <i class="fas fa-coins text-5xl text-green-500 mb-3"></i>
                        <h3 class="text-xl font-bold text-white mb-2">Mini Anuncio</h3>
                        <div class="text-3xl font-bold text-green-500">$100</div>
                        <div class="text-gray-400 text-sm">COP por click</div>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4 mb-4 min-h-[120px] flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <i class="fas fa-ad text-4xl mb-2"></i>
                            <p class="text-sm">Anuncio de 60 segundos</p>
                        </div>
                    </div>
                    <button onclick="simulateClick(100, 'mini')" class="w-full bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold py-3 rounded-lg hover:from-green-600 hover:to-emerald-600 transition transform hover:scale-105">
                        <i class="fas fa-mouse-pointer mr-2"></i>Hacer Click Demo
                    </button>
                    <div class="mt-3 text-center text-xs text-gray-400">
                        ⏱️ 60 segundos • 💎 Acumulable 30 días
                    </div>
                </div>
            </div>

            <!-- Mensaje Motivacional -->
            <div class="mt-12 text-center bg-gradient-to-r from-purple-900 to-pink-900 rounded-2xl p-8 border-2 border-purple-500">
                <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">🚀 ¡Imagina ganar esto TODOS LOS DÍAS!</h3>
                <p class="text-lg text-purple-200 mb-6">Con solo 15 minutos al día, podrías generar ingresos constantes desde casa</p>
                <button onclick="window.location.href='/register'" class="bg-white text-purple-600 px-8 py-4 rounded-full text-lg font-bold hover:bg-gray-100 transition transform hover:scale-105 shadow-xl">
                    <i class="fas fa-user-plus mr-2"></i>Regístrate GRATIS Ahora
                </button>
            </div>
        </div>
    </section>

    <script>
        let demoBalance = 0;
        let demoClicks = 0;

        function simulateClick(amount, type) {
            // Animar el botón
            event.target.disabled = true;
            event.target.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';

            setTimeout(() => {
                // Incrementar balance
                demoBalance += amount;
                demoClicks++;

                // Actualizar UI
                document.getElementById('demo-balance').textContent = '$' + demoBalance.toLocaleString('es-CO');
                document.getElementById('demo-clicks').textContent = demoClicks;

                // Animar billetera
                const wallet = document.getElementById('demo-wallet');
                wallet.classList.add('scale-110');
                setTimeout(() => wallet.classList.remove('scale-110'), 300);

                // Mostrar notificación
                showNotification(`¡+$${amount.toLocaleString('es-CO')} COP ganados!`, type);

                // Restaurar botón
                event.target.disabled = false;
                event.target.innerHTML = '<i class="fas fa-mouse-pointer mr-2"></i>Hacer Click Demo';
            }, 1500);
        }

        function showNotification(message, type) {
            const colors = {
                'mega': 'from-yellow-500 to-orange-500',
                'principal': 'from-cyan-500 to-blue-500',
                'mini': 'from-green-500 to-emerald-500'
            };

            const notification = document.createElement('div');
            notification.className = `fixed top-24 left-1/2 transform -translate-x-1/2 bg-gradient-to-r ${colors[type]} text-white px-6 py-3 rounded-full shadow-2xl z-50 font-bold animate-bounce`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 2000);
        }
    </script>

    <style>
        .banner-slider { position: relative; height: 250px; }
        @media (min-width: 768px) { .banner-slider { height: 400px; } }
        @media (min-width: 1024px) { .banner-slider { height: 500px; } }
        .banner-slide { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
        .banner-slide.active { display: block; animation: fadeIn 1s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.banner-slide');
        
        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        
        setInterval(nextSlide, 4000);
    </script>

    <!-- Cómo Funciona -->
    <section id="como-funciona" class="py-12 md:py-20 bg-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Video Explicativo -->
            <div class="mb-16 md:mb-20">
                <div class="text-center mb-8 md:mb-12">
                    <h2 class="text-3xl md:text-5xl font-bold text-primary mb-4">🎥 Así de fácil es ganar con nosotros</h2>
                    <p class="text-lg md:text-xl text-white">Mira cómo funciona nuestro modelo de negocio</p>
                </div>
                <div class="max-w-4xl mx-auto">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-primary" style="padding-bottom: 56.25%;">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                            title="Cómo ganar con PubliHazClick" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div class="text-center mt-8">
                        <button onclick="window.location.href='/register'" class="btn-primary px-8 py-4 rounded-full text-lg font-bold transition transform hover:scale-105 shadow-xl">
                            <i class="fas fa-rocket mr-2"></i>Regístrate en PubliHazClick
                        </button>
                        <p class="text-gray-400 mt-4 text-sm">* Necesitas un código de referido para registrarte</p>
                    </div>
                </div>
            </div>

            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-primary mb-4">¿Cómo Funciona?</h2>
                <p class="text-lg md:text-xl text-white">3 simples pasos para comenzar a ganar</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div class="bg-gray-900 border border-primary rounded-2xl p-6 md:p-8 card-hover">
                    <div class="w-14 h-14 md:w-16 md:h-16 gradient-primary rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="text-black text-xl md:text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-primary mb-3 md:mb-4 text-center">Regístrate</h3>
                    <p class="text-white text-center text-sm md:text-base">Crea tu cuenta gratis con un código de referido y elige tu paquete inicial desde $25 USD</p>
                </div>
                <div class="bg-gray-900 border border-primary rounded-2xl p-6 md:p-8 card-hover">
                    <div class="w-14 h-14 md:w-16 md:h-16 gradient-success rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="text-black text-xl md:text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-primary mb-3 md:mb-4 text-center">Haz Clicks</h3>
                    <p class="text-white text-center text-sm md:text-base">Ve anuncios diarios de 60-120 segundos y gana entre $100 y $2,000 COP por cada click</p>
                </div>
                <div class="bg-gray-900 border border-primary rounded-2xl p-6 md:p-8 card-hover">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="text-black text-xl md:text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-primary mb-3 md:mb-4 text-center">Retira</h3>
                    <p class="text-white text-center text-sm md:text-base">Acumula tus ganancias y retíralas por Nequi, Bancolombia, PayPal y más</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tipos de Anuncios -->
    <section class="py-12 md:py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-primary mb-4">Tipos de Anuncios</h2>
                <p class="text-lg md:text-xl text-white">Múltiples formas de ganar dinero</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div class="bg-black border-2 border-primary rounded-2xl p-6 md:p-8 card-hover">
                    <div class="text-center mb-4 md:mb-6">
                        <i class="fas fa-star text-5xl md:text-6xl mb-3 md:mb-4 text-primary"></i>
                        <h3 class="text-2xl md:text-3xl font-bold mb-2 text-primary">Mega Anuncios</h3>
                        <div class="text-3xl md:text-4xl font-bold text-white">$2,000</div>
                        <div class="text-gray-400 text-sm md:text-base">COP por click</div>
                    </div>
                    <ul class="space-y-2 md:space-y-3 text-white text-sm md:text-base">
                        <li class="flex items-center"><i class="fas fa-check-circle mr-3 text-primary"></i>120 segundos</li>
                        <li class="flex items-center"><i class="fas fa-check-circle mr-3 text-primary"></i>Bono por referidos</li>
                        <li class="flex items-center"><i class="fas fa-check-circle mr-3 text-primary"></i>Acumulables 30 días</li>
                    </ul>
                </div>
                <div class="bg-black border-2 border-primary rounded-2xl p-6 md:p-8 card-hover">
                    <div class="text-center mb-4 md:mb-6">
                        <i class="fas fa-bullhorn text-5xl md:text-6xl mb-3 md:mb-4 text-primary"></i>
                        <h3 class="text-2xl md:text-3xl font-bold mb-2 text-primary">Principales</h3>
                        <div class="text-3xl md:text-4xl font-bold text-white">$400-$1,600</div>
                        <div class="text-gray-400 text-sm md:text-base">COP por click</div>
                    </div>
                    <ul class="space-y-2 md:space-y-3 text-white text-sm md:text-base">
                        <li class="flex items-center"><i class="fas fa-check-circle mr-3 text-primary"></i>5 clicks diarios</li>
                        <li class="flex items-center"><i class="fas fa-check-circle mr-3 text-primary"></i>90 segundos</li>
                        <li class="flex items-center"><i class="fas fa-check-circle mr-3 text-primary"></i>Recarga diaria</li>
                    </ul>
                </div>
                <div class="bg-black border-2 border-primary rounded-2xl p-6 md:p-8 card-hover">
                    <div class="text-center mb-4 md:mb-6">
                        <i class="fas fa-coins text-5xl md:text-6xl mb-3 md:mb-4 text-primary"></i>
                        <h3 class="text-2xl md:text-3xl font-bold mb-2 text-primary">Mini Anuncios</h3>
                        <div class="text-3xl md:text-4xl font-bold text-white">$100</div>
                        <div class="text-gray-400 text-sm md:text-base">COP por click</div>
                    </div>
                    <ul class="space-y-2 md:space-y-3 text-white text-sm md:text-base">
                        <li class="flex items-center"><i class="fas fa-check-circle mr-3 text-primary"></i>4-8 clicks diarios</li>
                        <li class="flex items-center"><i class="fas fa-check-circle mr-3 text-primary"></i>60 segundos</li>
                        <li class="flex items-center"><i class="fas fa-check-circle mr-3 text-primary"></i>Acumulables 30 días</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Paquetes -->
    <section id="paquetes" class="py-12 md:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">Elige tu Paquete</h2>
                <p class="text-lg md:text-xl text-gray-600">Planes diseñados para maximizar tus ganancias</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Básico -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover border-2 border-gray-200">
                    <div class="text-center mb-6">
                        <div class="text-gray-600 font-semibold mb-2">Básico</div>
                        <div class="text-5xl font-bold text-gray-900 mb-2">$25</div>
                        <div class="text-gray-500">USD</div>
                    </div>
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg text-center">
                        <div class="text-sm text-gray-600">Ganancias mensuales</div>
                        <div class="text-2xl font-bold text-blue-600">~$70,000</div>
                        <div class="text-xs text-gray-500">COP/mes</div>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">5 clicks principales/día</span></li>
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">$400 COP por click</span></li>
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">4 mini-anuncios/día</span></li>
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">Categoría Jade</span></li>
                    </ul>
                    <button onclick="window.location.href='/register'" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                        Comenzar
                    </button>
                </div>

                <!-- Premium -->
                <div class="bg-white rounded-2xl shadow-xl p-8 card-hover border-2 border-green-500 relative transform md:scale-105">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-bold">MÁS POPULAR</span>
                    </div>
                    <div class="text-center mb-6">
                        <div class="text-gray-600 font-semibold mb-2">Premium</div>
                        <div class="text-5xl font-bold text-gray-900 mb-2">$50</div>
                        <div class="text-gray-500">USD</div>
                    </div>
                    <div class="mb-6 p-4 bg-green-50 rounded-lg text-center">
                        <div class="text-sm text-gray-600">Ganancias mensuales</div>
                        <div class="text-2xl font-bold text-green-600">~$141,000</div>
                        <div class="text-xs text-gray-500">COP/mes</div>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">5 clicks principales/día</span></li>
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">$600 COP por click</span></li>
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">4 mini-anuncios/día</span></li>
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">Categoría Jade</span></li>
                    </ul>
                    <button onclick="window.location.href='/register'" class="w-full gradient-success text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                        Comenzar
                    </button>
                </div>

                <!-- Avanzado -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover border-2 border-gray-200">
                    <div class="text-center mb-6">
                        <div class="text-gray-600 font-semibold mb-2">Avanzado</div>
                        <div class="text-5xl font-bold text-gray-900 mb-2">$100</div>
                        <div class="text-gray-500">USD</div>
                    </div>
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg text-center">
                        <div class="text-sm text-gray-600">Ganancias mensuales</div>
                        <div class="text-2xl font-bold text-yellow-600">~$180,000</div>
                        <div class="text-xs text-gray-500">COP/mes</div>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">5 clicks principales/día</span></li>
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">$1,120 COP por click</span></li>
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">4 mini-anuncios/día</span></li>
                        <li class="flex items-start"><i class="fas fa-check text-green-500 mr-3 mt-1"></i><span class="text-sm">Categoría Esmeralda</span></li>
                    </ul>
                    <button onclick="window.location.href='/register'" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                        Comenzar
                    </button>
                </div>

                <!-- Elite -->
                <div class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl shadow-lg p-8 card-hover text-white">
                    <div class="text-center mb-6">
                        <div class="text-purple-200 font-semibold mb-2">Elite</div>
                        <div class="text-5xl font-bold mb-2">$150</div>
                        <div class="text-purple-200">USD</div>
                    </div>
                    <div class="mb-6 p-4 bg-white bg-opacity-20 rounded-lg text-center">
                        <div class="text-sm text-purple-100">Ganancias mensuales</div>
                        <div class="text-2xl font-bold">~$384,000</div>
                        <div class="text-xs text-purple-200">COP/mes</div>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start"><i class="fas fa-check mr-3 mt-1"></i><span class="text-sm">5 clicks principales/día</span></li>
                        <li class="flex items-start"><i class="fas fa-check mr-3 mt-1"></i><span class="text-sm">$1,600 COP por click</span></li>
                        <li class="flex items-start"><i class="fas fa-check mr-3 mt-1"></i><span class="text-sm">8 mini-anuncios/día</span></li>
                        <li class="flex items-start"><i class="fas fa-check mr-3 mt-1"></i><span class="text-sm">Categoría Esmeralda</span></li>
                    </ul>
                    <button onclick="window.location.href='/register'" class="w-full bg-white text-purple-600 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Comenzar
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonios -->
    <section id="testimonios" class="py-12 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">Lo que dicen nuestros usuarios</h2>
                <p class="text-lg md:text-xl text-gray-600">Miles de personas ya están ganando con PubliHazClick</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div class="bg-gray-50 rounded-2xl p-6 md:p-8 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg md:text-xl">M</div>
                        <div class="ml-3 md:ml-4">
                            <div class="font-bold text-gray-900 text-sm md:text-base">María G.</div>
                            <div class="text-xs md:text-sm text-gray-600">Bogotá, Colombia</div>
                        </div>
                    </div>
                    <div class="text-yellow-400 mb-3 text-sm md:text-base">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 text-sm md:text-base">"Llevo 3 meses y ya he retirado más de $400,000 COP. Es increíble poder generar ingresos desde casa."</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-6 md:p-8 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg md:text-xl">C</div>
                        <div class="ml-3 md:ml-4">
                            <div class="font-bold text-gray-900 text-sm md:text-base">Carlos R.</div>
                            <div class="text-xs md:text-sm text-gray-600">Medellín, Colombia</div>
                        </div>
                    </div>
                    <div class="text-yellow-400 mb-3 text-sm md:text-base">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 text-sm md:text-base">"El sistema de referidos es excelente. Mis ganancias se multiplicaron cuando invité a mis amigos."</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-6 md:p-8 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg md:text-xl">A</div>
                        <div class="ml-3 md:ml-4">
                            <div class="font-bold text-gray-900 text-sm md:text-base">Ana L.</div>
                            <div class="text-xs md:text-sm text-gray-600">Cali, Colombia</div>
                        </div>
                    </div>
                    <div class="text-yellow-400 mb-3 text-sm md:text-base">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 text-sm md:text-base">"Plataforma confiable y pagos puntuales. Ya voy por mi segundo retiro exitoso."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Métodos de Pago -->
    <section class="py-12 md:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">Métodos de Retiro</h2>
                <p class="text-lg md:text-xl text-gray-600">Retira tus ganancias de forma segura y rápida</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <div class="bg-white rounded-xl p-4 md:p-6 shadow-md card-hover text-center">
                    <i class="fas fa-university text-3xl md:text-4xl text-red-600 mb-2 md:mb-3"></i>
                    <div class="font-bold text-gray-900 text-sm md:text-base">Bancolombia</div>
                </div>
                <div class="bg-white rounded-xl p-4 md:p-6 shadow-md card-hover text-center">
                    <i class="fas fa-mobile-alt text-3xl md:text-4xl text-purple-600 mb-2 md:mb-3"></i>
                    <div class="font-bold text-gray-900 text-sm md:text-base">Nequi</div>
                </div>
                <div class="bg-white rounded-xl p-4 md:p-6 shadow-md card-hover text-center">
                    <i class="fas fa-wallet text-3xl md:text-4xl text-orange-600 mb-2 md:mb-3"></i>
                    <div class="font-bold text-gray-900 text-sm md:text-base">Daviplata</div>
                </div>
                <div class="bg-white rounded-xl p-4 md:p-6 shadow-md card-hover text-center">
                    <i class="fab fa-paypal text-3xl md:text-4xl text-blue-600 mb-2 md:mb-3"></i>
                    <div class="font-bold text-gray-900 text-sm md:text-base">PayPal</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-12 md:py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">Preguntas Frecuentes</h2>
            </div>
            <div class="space-y-3 md:space-y-4">
                <details class="bg-gray-50 rounded-xl p-4 md:p-6 cursor-pointer">
                    <summary class="font-bold text-base md:text-lg text-gray-900 cursor-pointer">¿Cómo empiezo a ganar?</summary>
                    <p class="mt-3 md:mt-4 text-gray-600 text-sm md:text-base">Regístrate con un código de referido, elige tu paquete desde $25 USD y comienza a hacer clicks en anuncios diarios.</p>
                </details>
                <details class="bg-gray-50 rounded-xl p-4 md:p-6 cursor-pointer">
                    <summary class="font-bold text-base md:text-lg text-gray-900 cursor-pointer">¿Cuánto puedo ganar?</summary>
                    <p class="mt-3 md:mt-4 text-gray-600 text-sm md:text-base">Depende de tu paquete. Desde $70,000 COP/mes con el paquete básico hasta $384,000 COP/mes con el paquete elite, más bonos por referidos.</p>
                </details>
                <details class="bg-gray-50 rounded-xl p-4 md:p-6 cursor-pointer">
                    <summary class="font-bold text-base md:text-lg text-gray-900 cursor-pointer">¿Cuándo puedo retirar?</summary>
                    <p class="mt-3 md:mt-4 text-gray-600 text-sm md:text-base">Puedes retirar cada 30 días una vez alcances el mínimo de tu categoría y tengas al menos 1 referido activo.</p>
                </details>
                <details class="bg-gray-50 rounded-xl p-4 md:p-6 cursor-pointer">
                    <summary class="font-bold text-base md:text-lg text-gray-900 cursor-pointer">¿Cómo funciona el sistema de referidos?</summary>
                    <p class="mt-3 md:mt-4 text-gray-600 text-sm md:text-base">Ganas comisiones por cada click que hagan tus referidos, mega-anuncios bonus cuando compran paquetes, y mini-anuncios adicionales según tu categoría.</p>
                </details>
                <details class="bg-gray-50 rounded-xl p-4 md:p-6 cursor-pointer">
                    <summary class="font-bold text-base md:text-lg text-gray-900 cursor-pointer">¿Es seguro?</summary>
                    <p class="mt-3 md:mt-4 text-gray-600 text-sm md:text-base">Sí, somos una plataforma establecida con miles de usuarios activos y pagos verificables. Todos los retiros son procesados de forma segura.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-12 md:py-20 gradient-primary text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-5xl font-bold mb-4 md:mb-6">¡Comienza a Ganar Hoy!</h2>
            <p class="text-lg md:text-2xl mb-6 md:mb-8 text-orange-100">
                Únete a miles de usuarios que ya están generando ingresos con PubliHazClick
            </p>
            <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
                <button onclick="window.location.href='/register'" class="bg-white text-orange-600 px-6 py-3 md:px-10 md:py-4 rounded-lg text-base md:text-lg font-bold hover:bg-gray-100 transition transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>Registrarse Gratis
                </button>
                <button onclick="window.location.href='/login'" class="border-2 border-white text-white px-6 py-3 md:px-10 md:py-4 rounded-lg text-base md:text-lg font-bold hover:bg-white hover:text-orange-600 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                </button>
            </div>
            <div class="mt-8 md:mt-12 grid grid-cols-3 gap-4 md:gap-8 max-w-2xl mx-auto">
                <div>
                    <div class="text-2xl md:text-3xl font-bold mb-1 md:mb-2">5,000+</div>
                    <div class="text-orange-200 text-xs md:text-base">Usuarios Activos</div>
                </div>
                <div>
                    <div class="text-2xl md:text-3xl font-bold mb-1 md:mb-2">$50M+</div>
                    <div class="text-orange-200 text-xs md:text-base">COP Pagados</div>
                </div>
                <div>
                    <div class="text-2xl md:text-3xl font-bold mb-1 md:mb-2">98%</div>
                    <div class="text-orange-200 text-xs md:text-base">Satisfacción</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 mb-6 md:mb-8">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 md:w-10 md:h-10 gradient-primary rounded-lg flex items-center justify-center">
                            <i class="fas fa-mouse-pointer text-white text-sm md:text-base"></i>
                        </div>
                        <span class="text-lg md:text-xl font-bold">PubliHazClick</span>
                    </div>
                    <p class="text-gray-400 text-sm md:text-base">Gana dinero viendo anuncios desde casa.</p>
                </div>
                <div>
                    <h3 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Enlaces</h3>
                    <ul class="space-y-2 text-gray-400 text-xs md:text-sm">
                        <li><a href="#inicio" class="hover:text-white">Inicio</a></li>
                        <li><a href="#como-funciona" class="hover:text-white">Cómo Funciona</a></li>
                        <li><a href="#paquetes" class="hover:text-white">Paquetes</a></li>
                        <li><a href="#testimonios" class="hover:text-white">Testimonios</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Legal</h3>
                    <ul class="space-y-2 text-gray-400 text-xs md:text-sm">
                        <li><a href="#" class="hover:text-white">Términos y Condiciones</a></li>
                        <li><a href="#" class="hover:text-white">Política de Privacidad</a></li>
                        <li><a href="#" class="hover:text-white">Política de Retiros</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Contacto</h3>
                    <ul class="space-y-2 text-gray-400 text-xs md:text-sm">
                        <li><i class="fas fa-envelope mr-2"></i>soporte@publihazclik.com</li>
                        <li><i class="fab fa-whatsapp mr-2"></i>+57 300 123 4567</li>
                    </ul>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook text-xl md:text-2xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram text-xl md:text-2xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter text-xl md:text-2xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 md:pt-8 text-center text-gray-400 text-xs md:text-sm">
                <p>&copy; 2024 PubliHazClick. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
