<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PubliHazClick - Gana Dinero Viendo Anuncios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    <!-- Billetera Simulada Flotante -->
    <div class="fixed top-20 right-4 z-40 bg-white rounded-lg shadow-2xl p-4 max-w-xs">
        <p class="text-xs text-gray-600 mb-2 text-center">Este sería el dinero que podrías retirar si te conviertes en anunciante</p>
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-3">
            <i class="fas fa-wallet text-xl mb-1"></i>
            <p class="text-xs font-semibold">Acumulado de Retiro</p>
            <p class="text-2xl font-bold" id="simulatedBalance">$0</p>
        </div>
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-2 mt-2">
            <p class="text-xs font-semibold">Donaciones</p>
            <p class="text-lg font-bold" id="simulatedDonations">$0</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-mouse-pointer text-blue-600 text-2xl mr-2"></i>
                    <span class="text-2xl font-bold text-gray-800">PubliHazClick</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-4">
                    <a href="#demo" class="text-gray-700 hover:text-blue-600">Demo</a>
                    <a href="#paquetes" class="text-gray-700 hover:text-blue-600">Paquetes</a>
                    <button onclick="openLoginModal()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Iniciar Sesión
                    </button>
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <div x-show="mobileMenuOpen" x-transition class="md:hidden pb-3">
                <a href="#demo" class="block px-3 py-2 text-gray-700">Demo</a>
                <a href="#paquetes" class="block px-3 py-2 text-gray-700">Paquetes</a>
                <button onclick="openLoginModal()" class="block w-full text-left px-3 py-2 text-blue-600 font-semibold">
                    Iniciar Sesión
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-12 bg-gradient-to-br from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Gana Dinero con <span class="text-yellow-300">PubliHazClick</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8">
                Haz clicks en anuncios, refiere amigos y gana dinero todos los días
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="openLoginModal()" class="bg-green-500 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-green-600">
                    <i class="fas fa-rocket mr-2"></i>Comenzar Ahora
                </button>
                <a href="#demo" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100">
                    <i class="fas fa-play mr-2"></i>Ver Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section id="demo" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Prueba cómo funciona - Haz click en los anuncios</h2>
            
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
                    Anuncios Principales - $400 / $600 COP
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="border-2 border-blue-400 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('main', 400)">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white rounded-lg p-4 text-center">
                            <i class="fas fa-mouse-pointer text-3xl mb-2"></i>
                            <p class="font-bold">$400</p>
                        </div>
                    </div>
                    <div class="border-2 border-blue-400 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('main', 600)">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg p-4 text-center">
                            <i class="fas fa-mouse-pointer text-3xl mb-2"></i>
                            <p class="font-bold">$600</p>
                        </div>
                    </div>
                    <div class="border-2 border-blue-400 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('main', 400)">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white rounded-lg p-4 text-center">
                            <i class="fas fa-mouse-pointer text-3xl mb-2"></i>
                            <p class="font-bold">$400</p>
                        </div>
                    </div>
                    <div class="border-2 border-blue-400 rounded-lg p-4 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('main', 600)">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg p-4 text-center">
                            <i class="fas fa-mouse-pointer text-3xl mb-2"></i>
                            <p class="font-bold">$600</p>
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
                <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                    @for($i = 1; $i <= 6; $i++)
                    <div class="border-2 border-green-400 rounded-lg p-3 hover:shadow-lg transition cursor-pointer" onclick="simulateClick('mini', 100)">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 text-white rounded-lg p-3 text-center">
                            <i class="fas fa-hand-pointer text-2xl mb-1"></i>
                            <p class="text-xs font-bold">Mini {{ $i }}</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-8 text-center">
                <h2 class="text-3xl font-bold mb-4">¡Así de fácil es ganar con nosotros!</h2>
                <button onclick="openLoginModal()" class="bg-white text-green-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100">
                    Regístrate Ahora
                </button>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="paquetes" class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Elige tu Paquete</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white border-2 border-gray-200 rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-2">Básico</h3>
                    <div class="text-3xl font-bold text-blue-600 mb-4">$25</div>
                    <ul class="space-y-2 mb-6 text-sm">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 5 clicks diarios</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> $400 COP/click</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> $69,999/mes</li>
                    </ul>
                    <button onclick="openLoginModal()" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                        Comenzar
                    </button>
                </div>

                <div class="bg-white border-2 border-green-500 rounded-xl p-6 relative">
                    <span class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</span>
                    <h3 class="text-xl font-bold mb-2">Premium</h3>
                    <div class="text-3xl font-bold text-green-600 mb-4">$50</div>
                    <ul class="space-y-2 mb-6 text-sm">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 5 clicks diarios</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> $600 COP/click</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> $141,000/mes</li>
                    </ul>
                    <button onclick="openLoginModal()" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700">
                        Comenzar
                    </button>
                </div>

                <div class="bg-white border-2 border-yellow-500 rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-2">Avanzado</h3>
                    <div class="text-3xl font-bold text-yellow-600 mb-4">$100</div>
                    <ul class="space-y-2 mb-6 text-sm">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> 5 clicks diarios</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> $1,120 COP/click</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> $180,000/mes</li>
                    </ul>
                    <button onclick="openLoginModal()" class="w-full bg-yellow-600 text-white py-3 rounded-lg hover:bg-yellow-700">
                        Comenzar
                    </button>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-2">Elite</h3>
                    <div class="text-3xl font-bold mb-4">$150</div>
                    <ul class="space-y-2 mb-6 text-sm">
                        <li class="flex items-center"><i class="fas fa-check mr-2"></i> 5 clicks diarios</li>
                        <li class="flex items-center"><i class="fas fa-check mr-2"></i> $1,600 COP/click</li>
                        <li class="flex items-center"><i class="fas fa-check mr-2"></i> $384,000/mes</li>
                    </ul>
                    <button onclick="openLoginModal()" class="w-full bg-white text-purple-600 py-3 rounded-lg hover:bg-gray-100 font-semibold">
                        Comenzar
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6" x-data="{ isLogin: true }">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold" x-text="isLogin ? 'Iniciar Sesión' : 'Registrarse'"></h3>
                <button onclick="closeLoginModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form x-show="isLogin" class="space-y-4" onsubmit="handleLogin(event)">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="loginEmail" class="w-full px-4 py-3 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                    <input type="password" id="loginPassword" class="w-full px-4 py-3 border rounded-lg" required>
                </div>
                <div id="loginError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Iniciar Sesión
                </button>
                <div class="text-center">
                    <button type="button" @click="isLogin = false" class="text-blue-600 hover:underline">
                        ¿No tienes cuenta? Regístrate
                    </button>
                </div>
            </form>

            <div x-show="!isLogin" class="space-y-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                    <p class="text-yellow-700 text-sm">
                        <strong>⚠️ Importante:</strong> Para registrarte necesitas un código de referido.
                    </p>
                </div>
                <a href="{{ route('register') }}" class="block w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 text-center font-semibold">
                    Ir al Formulario de Registro
                </a>
                <div class="text-center">
                    <button type="button" @click="isLogin = true" class="text-blue-600 hover:underline">
                        ¿Ya tienes cuenta? Inicia sesión
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2026 PubliHazClick. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        let simulatedBalance = 0;
        let simulatedDonations = 0;

        function simulateClick(type, amount) {
            if (type === 'main') {
                simulatedDonations += 10;
                simulatedBalance += (amount - 10);
            } else {
                simulatedBalance += amount;
            }

            document.getElementById('simulatedBalance').textContent = '$' + simulatedBalance.toLocaleString('es-CO');
            document.getElementById('simulatedDonations').textContent = '$' + simulatedDonations.toLocaleString('es-CO');

            Swal.fire({
                title: '¡Excelente!',
                html: `<p>Esto es lo que podrías ganar por ver este anuncio si te conviertes en anunciante</p>
                       <p class="text-3xl font-bold text-green-600 mt-4">+$${amount.toLocaleString('es-CO')} COP</p>`,
                icon: 'success',
                confirmButtonText: 'Continuar'
            });
        }

        function openLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
        }

        async function handleLogin(event) {
            event.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const errorDiv = document.getElementById('loginError');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            errorDiv.classList.add('hidden');
            
            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    window.location.href = data.redirect || '/dashboard';
                } else {
                    errorDiv.textContent = data.message || 'Credenciales incorrectas';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                errorDiv.textContent = 'Error de conexión';
                errorDiv.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
