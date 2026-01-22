<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PubliClick - Sistema de Fidelización "Recomienda y Gana"</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#3B82F6',
                        'secondary': '#10B981',
                        'accent': '#F59E0B',
                        'dark': '#1F2937'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-mouse-pointer text-primary text-2xl mr-2"></i>
                        <span class="text-2xl font-bold text-dark">PubliClick</span>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#inicio" class="text-gray-700 hover:text-primary transition-colors">Inicio</a>
                    <a href="#beneficios" class="text-gray-700 hover:text-primary transition-colors">Beneficios</a>
                    <a href="#paquetes" class="text-gray-700 hover:text-primary transition-colors">Paquetes</a>
                    <a href="#como-funciona" class="text-gray-700 hover:text-primary transition-colors">¿Cómo Funciona?</a>
                    <button onclick="openLoginModal()" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        Iniciar Sesión
                    </button>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-primary">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                    <a href="#inicio" class="block px-3 py-2 text-gray-700 hover:text-primary">Inicio</a>
                    <a href="#beneficios" class="block px-3 py-2 text-gray-700 hover:text-primary">Beneficios</a>
                    <a href="#paquetes" class="block px-3 py-2 text-gray-700 hover:text-primary">Paquetes</a>
                    <a href="#como-funciona" class="block px-3 py-2 text-gray-700 hover:text-primary">¿Cómo Funciona?</a>
                    <button onclick="openLoginModal()" class="w-full text-left px-3 py-2 text-primary font-semibold">
                        Iniciar Sesión
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="pt-16 bg-gradient-to-br from-primary to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Gana Dinero con 
                        <span class="text-yellow-300">PubliClick</span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-blue-100">
                        Sistema de Fidelización "Recomienda y Gana"
                    </p>
                    <p class="text-lg mb-8 text-blue-100">
                        Haz clicks en anuncios, refiere amigos y gana dinero todos los días. 
                        ¡Hasta 5 niveles de comisiones por referidos!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <button onclick="openLoginModal()" class="bg-secondary text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-green-600 transition-colors">
                            <i class="fas fa-rocket mr-2"></i>
                            Comenzar Ahora
                        </button>
                        <button onclick="scrollToSection('como-funciona')" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                            <i class="fas fa-play mr-2"></i>
                            Ver Cómo Funciona
                        </button>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-300">$25</div>
                                <div class="text-sm text-blue-100">Inversión Inicial</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-300">5</div>
                                <div class="text-sm text-blue-100">Clicks Diarios</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-300">∞</div>
                                <div class="text-sm text-blue-100">Referidos</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-yellow-300">3</div>
                                <div class="text-sm text-blue-100">Niveles</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="beneficios" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-dark mb-4">5 Formas de Ganar Dinero</h2>
                <p class="text-xl text-gray-600">Con nuestro sistema "Recomienda y Gana"</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center">
                        <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-mouse-pointer text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-3">1. Clicks Propios</h3>
                        <p class="text-gray-600">
                            Gana dinero haciendo 5 clicks diarios en anuncios según tu paquete adquirido.
                        </p>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center">
                        <div class="bg-secondary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-3">2. Referidos Nivel 1</h3>
                        <p class="text-gray-600">
                            Gana comisiones por los 5 clicks diarios de cada persona que refiera directamente.
                        </p>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center">
                        <div class="bg-accent text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-network-wired text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-3">3. Referidos Nivel 2</h3>
                        <p class="text-gray-600">
                            Gana comisiones por los clicks de los referidos de tus referidos.
                        </p>
                    </div>
                </div>

                <!-- Benefit 4 -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center">
                        <div class="bg-purple-500 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-sitemap text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-3">4. Referidos Nivel 3</h3>
                        <p class="text-gray-600">
                            Tercer nivel de comisiones por una red aún más amplia de referidos.
                        </p>
                    </div>
                </div>

                <!-- Benefit 5 -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 hover:shadow-lg transition-shadow md:col-span-2 lg:col-span-1">
                    <div class="text-center">
                        <div class="bg-red-500 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-unlock text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-3">5. Mini-Anuncios</h3>
                        <p class="text-gray-600">
                            Desbloquea más anuncios según el número de referidos que tengas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="como-funciona" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-dark mb-4">¿Cómo Funciona?</h2>
                <p class="text-xl text-gray-600">Simple, fácil y rentable</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-4">Compra tu Paquete</h3>
                    <p class="text-gray-600">
                        Elige el paquete que mejor se adapte a tus necesidades y comienza a ganar.
                    </p>
                </div>

                <div class="text-center">
                    <div class="bg-secondary text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-4">Haz Clicks Diarios</h3>
                    <p class="text-gray-600">
                        Realiza 5 clicks diarios en los anuncios disponibles y gana dinero inmediatamente.
                    </p>
                </div>

                <div class="text-center">
                    <div class="bg-accent text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-4">Refiere y Multiplica</h3>
                    <p class="text-gray-600">
                        Invita amigos con tu código de referido y gana comisiones por sus clicks.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="paquetes" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-dark mb-4">Elige tu Paquete</h2>
                <p class="text-xl text-gray-600">Diferentes opciones para diferentes objetivos</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Basic Package -->
                <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-primary transition-colors">
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-dark mb-2">Básico</h3>
                        <div class="text-3xl font-bold text-primary mb-4">$25</div>
                        <ul class="text-left space-y-2 mb-6">
                            <li class="flex items-center"><i class="fas fa-check text-secondary mr-2"></i> 5 clicks diarios</li>
                            <li class="flex items-center"><i class="fas fa-check text-secondary mr-2"></i> $0.10 por click</li>
                            <li class="flex items-center"><i class="fas fa-check text-secondary mr-2"></i> Comisiones nivel 1</li>
                        </ul>
                        <button onclick="openLoginModal()" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-blue-600 transition-colors">
                            Comenzar
                        </button>
                    </div>
                </div>

                <!-- Premium Package -->
                <div class="bg-white border-2 border-secondary rounded-xl p-6 relative">
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                        <span class="bg-secondary text-white px-4 py-1 rounded-full text-sm font-semibold">Popular</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-dark mb-2">Premium</h3>
                        <div class="text-3xl font-bold text-secondary mb-4">$50</div>
                        <ul class="text-left space-y-2 mb-6">
                            <li class="flex items-center"><i class="fas fa-check text-secondary mr-2"></i> 5 clicks diarios</li>
                            <li class="flex items-center"><i class="fas fa-check text-secondary mr-2"></i> $0.15 por click</li>
                            <li class="flex items-center"><i class="fas fa-check text-secondary mr-2"></i> Comisiones 2 niveles</li>
                        </ul>
                        <button onclick="openLoginModal()" class="w-full bg-secondary text-white py-3 rounded-lg hover:bg-green-600 transition-colors">
                            Comenzar
                        </button>
                    </div>
                </div>

                <!-- VIP Package -->
                <div class="bg-white border-2 border-accent rounded-xl p-6">
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-dark mb-2">VIP</h3>
                        <div class="text-3xl font-bold text-accent mb-4">$100</div>
                        <ul class="text-left space-y-2 mb-6">
                            <li class="flex items-center"><i class="fas fa-check text-secondary mr-2"></i> 5 clicks diarios</li>
                            <li class="flex items-center"><i class="fas fa-check text-secondary mr-2"></i> $0.20 por click</li>
                            <li class="flex items-center"><i class="fas fa-check text-secondary mr-2"></i> Comisiones 3 niveles</li>
                        </ul>
                        <button onclick="openLoginModal()" class="w-full bg-accent text-white py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                            Comenzar
                        </button>
                    </div>
                </div>

                <!-- Elite Package -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6">
                    <div class="text-center">
                        <h3 class="text-xl font-bold mb-2">Elite</h3>
                        <div class="text-3xl font-bold mb-4">$200</div>
                        <ul class="text-left space-y-2 mb-6">
                            <li class="flex items-center"><i class="fas fa-check mr-2"></i> 5 clicks diarios</li>
                            <li class="flex items-center"><i class="fas fa-check mr-2"></i> $0.25 por click</li>
                            <li class="flex items-center"><i class="fas fa-check mr-2"></i> Comisiones 3 niveles</li>
                            <li class="flex items-center"><i class="fas fa-check mr-2"></i> Mini-anuncios extra</li>
                        </ul>
                        <button onclick="openLoginModal()" class="w-full bg-white text-purple-600 py-3 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                            Comenzar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary to-secondary text-white">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold mb-6">¿Listo para Comenzar a Ganar?</h2>
            <p class="text-xl mb-8">
                Únete a miles de usuarios que ya están generando ingresos pasivos con PubliClick
            </p>
            <button onclick="openLoginModal()" class="bg-white text-primary px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                <i class="fas fa-rocket mr-2"></i>
                Comenzar Ahora - Solo $25
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-mouse-pointer text-primary text-2xl mr-2"></i>
                        <span class="text-2xl font-bold">PubliClick</span>
                    </div>
                    <p class="text-gray-400">
                        Sistema de fidelización que te permite ganar dinero haciendo clicks y refiriendo amigos.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Enlaces</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#inicio" class="hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="#beneficios" class="hover:text-white transition-colors">Beneficios</a></li>
                        <li><a href="#paquetes" class="hover:text-white transition-colors">Paquetes</a></li>
                        <li><a href="#como-funciona" class="hover:text-white transition-colors">¿Cómo Funciona?</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Soporte</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Centro de Ayuda</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Términos y Condiciones</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Política de Privacidad</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contacto</h4>
                    <div class="space-y-2 text-gray-400">
                        <p><i class="fas fa-envelope mr-2"></i> soporte@publiclik.com</p>
                        <p><i class="fas fa-phone mr-2"></i> +57 310 438 4019</p>
                        <div class="flex space-x-4 mt-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 PubliClick. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6" x-data="{ isLogin: true }">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-dark" x-text="isLogin ? 'Iniciar Sesión' : 'Registrarse'"></h3>
                <button onclick="closeLoginModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Login Form -->
            <form x-show="isLogin" class="space-y-4" onsubmit="handleLogin(event)">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="loginEmail" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="tu@email.com" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                    <input type="password" id="loginPassword" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="••••••••" required>
                </div>
                <div id="loginError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-blue-600 transition-colors font-semibold">
                    Iniciar Sesión
                </button>
                <div class="text-center">
                    <button type="button" @click="isLogin = false" class="text-primary hover:underline">
                        ¿No tienes cuenta? Regístrate
                    </button>
                </div>
            </form>

            <!-- Register Form -->
            <form x-show="!isLogin" class="space-y-4" onsubmit="handleRegister(event)">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                    <input type="text" id="registerName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Tu nombre completo" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="registerEmail" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="tu@email.com" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                    <input type="password" id="registerPassword" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="••••••••" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                    <input type="password" id="registerPasswordConfirm" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="••••••••" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Código de Referido (Opcional)</label>
                    <input type="text" id="registerReferralCode" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Código de quien te refirió">
                </div>
                <div id="registerError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>
                <button type="submit" class="w-full bg-secondary text-white py-3 rounded-lg hover:bg-green-600 transition-colors font-semibold">
                    Registrarse
                </button>
                <div class="text-center">
                    <button type="button" @click="isLogin = true" class="text-primary hover:underline">
                        ¿Ya tienes cuenta? Inicia sesión
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({
                behavior: 'smooth'
            });
        }

        async function handleLogin(event) {
            event.preventDefault();
            
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const errorDiv = document.getElementById('loginError');
            
            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ email, password })
                });
                
                if (response.ok) {
                    window.location.href = '/dashboard';
                } else {
                    const data = await response.json();
                    errorDiv.textContent = data.message || 'Credenciales incorrectas';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                errorDiv.textContent = 'Error de conexión';
                errorDiv.classList.remove('hidden');
            }
        }

        async function handleRegister(event) {
            event.preventDefault();
            
            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const passwordConfirm = document.getElementById('registerPasswordConfirm').value;
            const referralCode = document.getElementById('registerReferralCode').value;
            const errorDiv = document.getElementById('registerError');
            
            if (password !== passwordConfirm) {
                errorDiv.textContent = 'Las contraseñas no coinciden';
                errorDiv.classList.remove('hidden');
                return;
            }
            
            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ 
                        name, 
                        email, 
                        password, 
                        password_confirmation: passwordConfirm,
                        referral_code: referralCode 
                    })
                });
                
                if (response.ok) {
                    window.location.href = '/dashboard';
                } else {
                    const data = await response.json();
                    errorDiv.textContent = data.message || 'Error en el registro';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                errorDiv.textContent = 'Error de conexión';
                errorDiv.classList.remove('hidden');
            }
        }

        // Close modal when clicking outside
        document.getElementById('loginModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginModal();
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>