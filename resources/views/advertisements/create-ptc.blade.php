<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea tu Publicidad PTC - PubliHazClick</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">🎯 Crea tu Publicidad PTC</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($userAd)
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-blue-700">✅ Ya tienes una publicidad PTC activa: <strong>{{ $userAd->title }}</strong></p>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 underline">Volver al Dashboard</a>
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                    <p class="text-yellow-700">⚠️ <strong>IMPORTANTE:</strong> No podrás realizar tareas hasta que subas tu publicidad PTC y Banner</p>
                </div>

                <form action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data" id="ptcForm">
                    @csrf
                    <input type="hidden" name="type" value="ptc">

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Título de tu anuncio</label>
                        <input type="text" name="title" required 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Promoción especial de mi negocio">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Imagen o Video (Cuadrado recomendado)</label>
                        <input type="file" name="file" required accept="image/*,video/*"
                            class="w-full px-4 py-2 border rounded-lg" id="fileInput">
                        <p class="text-sm text-gray-500 mt-1">Formatos: JPG, PNG, GIF, MP4, WEBM (Máx. 20MB)</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Link de redirección</label>
                        <input type="url" name="redirect_url" required 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="https://tu-sitio-web.com">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Descripción (Opcional)</label>
                        <textarea name="description" rows="4" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Describe tu anuncio..."></textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                            💾 Guardar Publicidad
                        </button>
                        <button type="button" onclick="previewAd()" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                            👁️ Previsualización
                        </button>
                    </div>
                </form>

                <div class="mt-8 border-t pt-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">🔄 O clona un anuncio existente</h3>
                    <p class="text-gray-600 mb-4">Si no tienes publicidad propia, puedes clonar un anuncio de otro usuario</p>
                    <button onclick="showCloneOptions()" class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700">
                        📋 Ver anuncios disponibles para clonar
                    </button>
                </div>
            @endif
        </div>
    </div>

    <script>
        function previewAd() {
            const form = document.getElementById('ptcForm');
            const formData = new FormData(form);
            
            if (!form.checkValidity()) {
                alert('Por favor completa todos los campos obligatorios');
                return;
            }

            const previewWindow = window.open('', '_blank', 'width=800,height=600');
            previewWindow.document.write('<h2>Cargando previsualización...</h2>');
            
            fetch('{{ route("advertisements.preview") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.text())
            .then(html => {
                previewWindow.document.open();
                previewWindow.document.write(html);
                previewWindow.document.close();
            });
        }

        function showCloneOptions() {
            alert('Funcionalidad de clonado en desarrollo. Próximamente podrás ver anuncios de otros usuarios.');
        }
    </script>
</body>
</html>
