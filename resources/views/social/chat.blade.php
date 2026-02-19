<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat con {{ $friend->name }} - PubliHazClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white text-blue-500 rounded-full flex items-center justify-center font-bold">
                        {{ substr($friend->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold">{{ $friend->name }}</p>
                        <p class="text-sm opacity-90">{{ $friend->email }}</p>
                    </div>
                </div>
                <a href="{{ route('social.friends') }}" class="text-white hover:underline">← Volver</a>
            </div>

            <!-- Messages -->
            <div class="h-96 overflow-y-auto p-6 space-y-4" id="messages-container">
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs {{ $message->sender_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }} rounded-lg px-4 py-2">
                            <p>{{ $message->message }}</p>
                            <p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }} mt-1">
                                {{ $message->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input -->
            <form action="{{ route('social.message.send', $friend->id) }}" method="POST" class="border-t p-4">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="message" required placeholder="Escribe un mensaje..." class="flex-1 px-4 py-2 border rounded-lg">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                        📤 Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const container = document.getElementById('messages-container');
        container.scrollTop = container.scrollHeight;
    </script>
</body>
</html>
