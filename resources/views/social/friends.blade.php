<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Amigos - PubliHazClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold mb-6">👥 Mis Amigos</h1>

            <div class="mb-6">
                <a href="{{ route('social.feed') }}" class="text-blue-600 hover:underline">← Volver al Feed</a>
            </div>

            @if($friends->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($friends as $friendship)
                        <div class="border rounded-lg p-4 hover:shadow-lg transition">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                    {{ substr($friendship->friend->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-lg">{{ $friendship->friend->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $friendship->friend->email }}</p>
                                    @if($friendship->friend->currentRank)
                                        <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded mt-1">
                                            {{ $friendship->friend->currentRank->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('social.chat', $friendship->friend->id) }}" class="block w-full bg-blue-500 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-600">
                                    💬 Chatear
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg mb-4">Aún no tienes amigos</p>
                    <a href="{{ route('social.search') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                        🔍 Buscar Usuarios
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
