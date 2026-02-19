<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuarios - PubliHazClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold mb-6">🔍 Buscar Usuarios</h1>

            <div class="mb-6">
                <a href="{{ route('social.feed') }}" class="text-blue-600 hover:underline">← Volver al Feed</a>
            </div>

            <form action="{{ route('social.search') }}" method="GET" class="mb-6">
                <div class="flex gap-2">
                    <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Buscar por nombre..." class="flex-1 px-4 py-2 border rounded-lg">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">Buscar</button>
                </div>
            </form>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($users) && $users->count() > 0)
                <div class="space-y-4">
                    @foreach($users as $user)
                        <div class="border rounded-lg p-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                </div>
                            </div>
                            <form action="{{ route('social.friend.send', $user->id) }}" method="POST">
                                @csrf
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                    ➕ Agregar
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @elseif(isset($query))
                <p class="text-center text-gray-500 py-8">No se encontraron usuarios</p>
            @endif
        </div>
    </div>
</body>
</html>
