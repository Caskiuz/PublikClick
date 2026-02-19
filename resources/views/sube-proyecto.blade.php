@extends('layout')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">📤 Sube tu Proyecto</h1>
            <p class="text-teal-100">Comparte tu proyecto social con la comunidad</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Nombre del Proyecto</label>
                    <input type="text" class="w-full px-4 py-2 border rounded-lg" placeholder="Ej: Centro Comunitario">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Descripción</label>
                    <textarea class="w-full px-4 py-2 border rounded-lg" rows="5" placeholder="Describe tu proyecto..."></textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Categoría</label>
                        <select class="w-full px-4 py-2 border rounded-lg">
                            <option>Salud</option>
                            <option>Educación</option>
                            <option>Medio Ambiente</option>
                            <option>Infraestructura</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Meta de Donación (COP)</label>
                        <input type="number" class="w-full px-4 py-2 border rounded-lg" placeholder="500000">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Imagen del Proyecto</label>
                    <input type="file" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-lg">
                    📤 Enviar Proyecto
                </button>
            </form>
        </div>
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <h4 class="font-bold text-blue-800 mb-2">ℹ️ Requisitos</h4>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• El proyecto debe ser de beneficio comunitario</li>
                <li>• Será revisado por el equipo antes de publicarse</li>
                <li>• Recibirás notificación cuando sea aprobado</li>
            </ul>
        </div>
    </div>
</div>
@endsection
