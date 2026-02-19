@extends('layout')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-orange-600 to-yellow-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">🖼️ Crea tu Publicidad Banner</h1>
            <p class="text-orange-100">Banners visibles en toda la plataforma</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Nombre de la Campaña</label>
                    <input type="text" class="w-full px-4 py-2 border rounded-lg" placeholder="Ej: Campaña Verano 2024">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">URL de Destino</label>
                    <input type="url" class="w-full px-4 py-2 border rounded-lg" placeholder="https://tuwebsite.com">
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Tamaño del Banner</label>
                        <select class="w-full px-4 py-2 border rounded-lg">
                            <option>728x90 (Leaderboard) - $30 USD</option>
                            <option>300x250 (Medium Rectangle) - $25 USD</option>
                            <option>160x600 (Wide Skyscraper) - $35 USD</option>
                            <option>320x50 (Mobile Banner) - $20 USD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Posición</label>
                        <select class="w-full px-4 py-2 border rounded-lg">
                            <option>Header (Superior)</option>
                            <option>Sidebar (Lateral)</option>
                            <option>Footer (Inferior)</option>
                            <option>Entre contenido</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Duración de la Campaña</label>
                    <select class="w-full px-4 py-2 border rounded-lg">
                        <option>7 días - $30 USD</option>
                        <option>15 días - $50 USD</option>
                        <option>30 días - $80 USD</option>
                        <option>60 días - $140 USD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Imagen del Banner</label>
                    <input type="file" class="w-full px-4 py-2 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF. Tamaño según el banner seleccionado</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold mb-2">📊 Estadísticas Incluidas</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>✓ Impresiones totales</li>
                        <li>✓ Clicks recibidos</li>
                        <li>✓ CTR (Click Through Rate)</li>
                        <li>✓ Reporte diario por email</li>
                    </ul>
                </div>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-lg">
                    🖼️ Crear Banner
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
