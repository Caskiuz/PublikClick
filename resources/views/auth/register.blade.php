<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - PubliHazClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black min-h-screen flex items-center justify-center py-8">
    <div class="max-w-2xl w-full bg-gray-900 rounded-lg shadow-2xl p-8 border border-cyan-500">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-cyan-400">PubliHazClick</h1>
            <p class="text-gray-300">Únete y Comienza a Ganar</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-900 border border-red-500 text-red-200 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            
            <!-- Información Personal -->
            <div class="mb-6">
                <h3 class="text-cyan-400 font-semibold mb-3">📋 Información Personal</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-300 text-sm font-bold mb-2">Nombre Completo *</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                               required>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-bold mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                               required>
                    </div>
                </div>
            </div>

            <!-- Contraseñas -->
            <div class="mb-6">
                <h3 class="text-cyan-400 font-semibold mb-3">🔒 Seguridad</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-300 text-sm font-bold mb-2">Contraseña *</label>
                        <input type="password" name="password" 
                               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                               required>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-bold mb-2">Confirmar Contraseña *</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                               required>
                    </div>
                </div>
            </div>

            <!-- Avatar y WhatsApp -->
            <div class="mb-6">
                <h3 class="text-cyan-400 font-semibold mb-3">👤 Perfil</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-300 text-sm font-bold mb-2">Avatar URL</label>
                        <input type="url" name="avatar" value="{{ old('avatar') }}" 
                               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                               placeholder="https://ejemplo.com/avatar.jpg">
                        <p class="text-xs text-gray-500 mt-1">URL de tu foto de perfil</p>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-bold mb-2">WhatsApp *</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" 
                               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                               placeholder="+57 300 123 4567" required>
                    </div>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="mb-6">
                <h3 class="text-cyan-400 font-semibold mb-3">🌍 Ubicación</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-300 text-sm font-bold mb-2">País *</label>
                        <select name="country" id="country" 
                                class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                                required>
                            <option value="">Seleccionar...</option>
                            <option value="CO" {{ old('country') == 'CO' ? 'selected' : '' }}>Colombia</option>
                            <option value="MX" {{ old('country') == 'MX' ? 'selected' : '' }}>México</option>
                            <option value="AR" {{ old('country') == 'AR' ? 'selected' : '' }}>Argentina</option>
                            <option value="PE" {{ old('country') == 'PE' ? 'selected' : '' }}>Perú</option>
                            <option value="CL" {{ old('country') == 'CL' ? 'selected' : '' }}>Chile</option>
                            <option value="EC" {{ old('country') == 'EC' ? 'selected' : '' }}>Ecuador</option>
                            <option value="VE" {{ old('country') == 'VE' ? 'selected' : '' }}>Venezuela</option>
                            <option value="ES" {{ old('country') == 'ES' ? 'selected' : '' }}>España</option>
                            <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>Estados Unidos</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-bold mb-2">Departamento/Estado *</label>
                        <input type="text" name="state" value="{{ old('state') }}" 
                               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                               placeholder="Ej: Antioquia" required>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-bold mb-2">Ciudad *</label>
                        <input type="text" name="city" value="{{ old('city') }}" 
                               class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                               placeholder="Ej: Medellín" required>
                    </div>
                </div>
            </div>

            <!-- Código de Referido -->
            <div class="mb-6">
                <h3 class="text-cyan-400 font-semibold mb-3">🔗 Referido</h3>
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2">Código de Referido *</label>
                    <input type="text" name="referral_code" value="{{ old('referral_code', request('ref')) }}" 
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:border-cyan-500" 
                           placeholder="Código de quien te refirió" required>
                    <p class="text-xs text-red-400 mt-1">* Campo obligatorio para registrarse</p>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-cyan-500 to-blue-500 text-white py-3 px-4 rounded-lg hover:from-cyan-600 hover:to-blue-600 transition-all font-bold text-lg">
                🚀 Crear Mi Cuenta
            </button>
        </form>

        <div class="text-center mt-6 space-y-2">
            <a href="{{ route('login') }}" class="block text-cyan-400 hover:text-cyan-300">
                ¿Ya tienes cuenta? Inicia sesión
            </a>
            <a href="/" class="block text-gray-400 hover:text-gray-300">
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>