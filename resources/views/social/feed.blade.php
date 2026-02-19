<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amigos - PubliHazClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">👤 {{ auth()->user()->name }}</h2>
                    <div class="space-y-2">
                        <a href="{{ route('social.feed') }}" class="block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">📰 Feed</a>
                        <a href="{{ route('social.friends') }}" class="block px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">👥 Mis Amigos</a>
                        <a href="{{ route('social.search') }}" class="block px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">🔍 Buscar Usuarios</a>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">🏠 Dashboard</a>
                    </div>
                </div>

                @if($friendRequests->count() > 0)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-bold mb-4">🔔 Solicitudes ({{ $friendRequests->count() }})</h3>
                        @foreach($friendRequests as $request)
                            <div class="border-b pb-3 mb-3">
                                <p class="font-semibold">{{ $request->user->name }}</p>
                                <div class="flex gap-2 mt-2">
                                    <form action="{{ route('social.accept', $request->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">✅ Aceptar</button>
                                    </form>
                                    <form action="{{ route('social.reject', $request->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">❌ Rechazar</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Main Feed -->
            <div class="lg:col-span-2">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Create Post -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">✍️ Crear Publicación</h3>
                    <form action="{{ route('social.post.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea name="content" rows="3" required placeholder="¿Qué estás pensando?" class="w-full px-4 py-2 border rounded-lg mb-3"></textarea>
                        <input type="file" name="image" accept="image/*" class="mb-3">
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">📤 Publicar</button>
                    </form>
                </div>

                <!-- Posts -->
                @foreach($posts as $post)
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-bold">{{ $post->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <p class="mb-4">{{ $post->content }}</p>

                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="w-full rounded-lg mb-4">
                        @endif

                        <div class="flex items-center gap-6 border-t pt-4">
                            <button onclick="likePost({{ $post->id }})" class="flex items-center gap-2 text-gray-600 hover:text-red-500" id="like-btn-{{ $post->id }}">
                                <span class="text-2xl">{{ $post->isLikedBy(auth()->id()) ? '❤️' : '🤍' }}</span>
                                <span id="likes-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                            </button>
                            <button onclick="toggleComments({{ $post->id }})" class="flex items-center gap-2 text-gray-600 hover:text-blue-500">
                                <span class="text-2xl">💬</span>
                                <span>{{ $post->comments_count }}</span>
                            </button>
                        </div>

                        <!-- Comments Section -->
                        <div id="comments-{{ $post->id }}" class="hidden mt-4 border-t pt-4">
                            <form action="{{ route('social.post.comment', $post->id) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="text" name="comment" required placeholder="Escribe un comentario..." class="flex-1 px-4 py-2 border rounded-lg">
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Enviar</button>
                                </div>
                            </form>

                            @foreach($post->comments as $comment)
                                <div class="bg-gray-50 rounded-lg p-3 mb-2">
                                    <p class="font-semibold text-sm">{{ $comment->user->name }}</p>
                                    <p class="text-sm">{{ $comment->comment }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{ $posts->links() }}
            </div>
        </div>
    </div>

    <script>
        function likePost(postId) {
            fetch(`/social/post/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const btn = document.getElementById(`like-btn-${postId}`);
                const count = document.getElementById(`likes-count-${postId}`);
                btn.querySelector('span').textContent = data.liked ? '❤️' : '🤍';
                count.textContent = data.likes_count;
            });
        }

        function toggleComments(postId) {
            const comments = document.getElementById(`comments-${postId}`);
            comments.classList.toggle('hidden');
        }
    </script>
</body>
</html>
