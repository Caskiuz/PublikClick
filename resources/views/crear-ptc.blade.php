@extends('layout')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">🎯 Crea tu Publicidad PTC</h1>
            <p class="text-red-100">Promociona tu negocio con anuncios PTC</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Título del Anuncio</label>
                    <input type="text" class="w-full px-4 py-2 border rounded-lg" placeholder="Ej: Visita mi tienda online" maxlength="60">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Descripción</label>
                    <textarea class="w-full px-4 py-2 border rounded-lg" rows="4" placeholder="Describe tu anuncio..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">URL de Destino</label>
                    <input type="url" class="w-full px-4 py-2 border rounded-lg" placeholder="https://tuwebsite.com">
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Tipo de Anuncio</label>
                        <select class="w-full px-4 py-2 border rounded-lg">
                            <option>Principal (90s) - $50 USD</option>
                            <option>Mini (60s) - $25 USD</option>
                            <option>Mega (120s) - $100 USD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Duración</label>
                        <select class="w-full px-4 py-2 border rounded-lg">
                            <option>7 días</option>
                            <option>15 días</option>
                            <option>30 días</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Imagen del Anuncio (800x600px)</label>
                    <input type="file" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold mb-2">💰 Resumen de Costos</h4>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Tipo de anuncio:</span>
                        <span class="font-semibold">$50 USD</span>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Duración (7 días):</span>
                        <span class="font-semibold">$50 USD</span>
                    </div>
                    <div class="border-t pt-2 mt-2 flex justify-between font-bold">
                        <span>Total:</span>
                        <span class="text-green-600">$50 USD</span>
                    </div>
                </div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg">
                    🎯 Crear Anuncio PTC
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
