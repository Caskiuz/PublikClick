<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro - PubliHazClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Regístrate en PubliHazClick</h1>
            <p class="text-gray-600">Completa todos los campos para crear tu cuenta</p>
        </div>

        @if(!request()->has('ref'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">¡Atención!</p>
            <p>Para registrarte en nuestro sitio necesitas el link de alguien que ya participe en nuestro sistema.</p>
        </div>
        @endif

        <form id="registerForm" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombres y Apellidos -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i> Nombres y Apellidos *
                    </label>
                    <input type="text" name="name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Juan Pérez García">
                </div>

                <!-- Número de WhatsApp -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fab fa-whatsapp mr-1"></i> Número de WhatsApp *
                    </label>
                    <input type="tel" name="whatsapp" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="+57 300 123 4567">
                </div>

                <!-- Correo Electrónico -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1"></i> Correo Electrónico *
                    </label>
                    <input type="email" name="email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="tu@email.com">
                </div>

                <!-- Avatar URL -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-image mr-1"></i> Avatar (URL de imagen)
                    </label>
                    <input type="url" name="avatar"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="https://ejemplo.com/mi-foto.jpg">
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-1"></i> Crea tu Contraseña *
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="••••••••">
                </div>

                <!-- Repetir Contraseña -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-1"></i> Repetir Contraseña *
                    </label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="••••••••">
                </div>

                <!-- País -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-globe mr-1"></i> País *
                    </label>
                    <select name="country" id="country" required onchange="updateStates()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Selecciona tu país</option>
                        <option value="CO">Colombia</option>
                        <option value="MX">México</option>
                        <option value="AR">Argentina</option>
                        <option value="PE">Perú</option>
                        <option value="CL">Chile</option>
                        <option value="EC">Ecuador</option>
                        <option value="VE">Venezuela</option>
                        <option value="BO">Bolivia</option>
                        <option value="PY">Paraguay</option>
                        <option value="UY">Uruguay</option>
                        <option value="ES">España</option>
                        <option value="US">Estados Unidos</option>
                    </select>
                </div>

                <!-- Departamento/Estado/Provincia -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i> <span id="stateLabel">Departamento/Estado</span> *
                    </label>
                    <input type="text" name="state" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ej: Antioquia, California, Buenos Aires">
                </div>

                <!-- Ciudad -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-city mr-1"></i> Ciudad *
                    </label>
                    <input type="text" name="city" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ej: Medellín, Los Ángeles">
                </div>

                <!-- Código de Referido -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-users mr-1"></i> Código de Referido *
                    </label>
                    <input type="text" name="referral_code" required readonly
                           value="{{ request()->get('ref', '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Código de quien te refirió">
                    @if(request()->has('ref'))
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-check-circle"></i> Código de referido válido
                    </p>
                    @endif
                </div>
            </div>

            <!-- Términos y Condiciones -->
            <div class="flex items-start">
                <input type="checkbox" name="terms" required
                       class="mt-1 mr-2 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label class="text-sm text-gray-600">
                    Acepto los <a href="#" class="text-blue-600 hover:underline">términos y condiciones</a> 
                    y la <a href="#" class="text-blue-600 hover:underline">política de privacidad</a> de PubliHazClick
                </label>
            </div>

            <!-- Botón de Registro -->
            <div class="text-center">
                <button type="submit" 
                        class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-purple-600 text-white px-12 py-4 rounded-lg text-lg font-bold hover:shadow-xl transition transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Mi Cuenta
                </button>
            </div>

            <!-- Link de Login -->
            <div class="text-center">
                <p class="text-gray-600">
                    ¿Ya tienes cuenta? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Inicia Sesión</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        function updateStates() {
            const country = document.getElementById('country').value;
            const stateLabel = document.getElementById('stateLabel');
            
            const labels = {
                'CO': 'Departamento',
                'MX': 'Estado',
                'AR': 'Provincia',
                'ES': 'Provincia',
                'US': 'Estado'
            };
            
            stateLabel.textContent = labels[country] || 'Departamento/Estado/Provincia';
        }

        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Validar que tenga código de referido
            if (!data.referral_code) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Para registrarte en nuestro sitio necesitas el link de alguien que ya participe en nuestro sistema'
                });
                return;
            }
            
            // Validar contraseñas
            if (data.password !== data.password_confirmation) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden'
                });
                return;
            }
            
            try {
                const response = await fetch('{{ route("register") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Registro Exitoso!',
                        text: 'Tu cuenta ha sido creada correctamente',
                        confirmButtonText: 'Continuar'
                    }).then(() => {
                        window.location.href = '/dashboard';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en el Registro',
                        text: result.message || 'Ocurrió un error al crear tu cuenta'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor intenta nuevamente.'
                });
            }
        });
    </script>
</body>
</html>
