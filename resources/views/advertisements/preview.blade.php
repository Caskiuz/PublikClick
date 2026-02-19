<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previsualización - {{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
                <h2 class="text-white text-2xl font-bold">👁️ Previsualización: {{ $title }}</h2>
            </div>

            <div class="p-6">
                @if($file_type === 'video')
                    <video controls class="w-full rounded-lg mb-4">
                        <source src="{{ asset('storage/' . $file_path) }}" type="video/mp4">
                    </video>
                @else
                    <img src="{{ asset('storage/' . $file_path) }}" alt="{{ $title }}" 
                        class="w-full rounded-lg mb-4 {{ $type === 'banner' ? 'max-h-32' : '' }}">
                @endif

                @if($description)
                    <p class="text-gray-700 mb-4">{{ $description }}</p>
                @endif

                <div class="flex items-center gap-4 mb-4 text-gray-600">
                    <button class="flex items-center gap-2 hover:text-red-500">
                        <span class="text-2xl">❤️</span>
                        <span>Me gusta (0)</span>
                    </button>
                    <button class="flex items-center gap-2 hover:text-blue-500">
                        <span class="text-2xl">💬</span>
                        <span>Comentar (0)</span>
                    </button>
                    <button class="flex items-center gap-2 hover:text-green-500">
                        <span class="text-2xl">🔗</span>
                        <span>Compartir (0)</span>
                    </button>
                </div>

                <a href="{{ $redirect_url }}" target="_blank" 
                    class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700">
                    🔗 Visitar sitio web
                </a>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button onclick="window.close()" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                Cerrar previsualización
            </button>
        </div>
    </div>
</body>
</html>
