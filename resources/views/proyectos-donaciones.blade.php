@extends('layout')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">❤️ Proyectos de Donaciones</h1>
            <p class="text-purple-100">Tu acumulado de donaciones apoya estos proyectos</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Tu Acumulado de Donaciones</p>
                    <p class="text-4xl font-bold text-purple-600">${{ number_format(Auth::user()->donationWallet->balance ?? 0, 0, ',', '.') }} COP</p>
                </div>
                <div class="text-6xl">🎁</div>
            </div>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @for($i = 1; $i <= 6; $i++)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white text-6xl">🏥</div>
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-2">Proyecto Comunitario {{ $i }}</h3>
                    <p class="text-sm text-gray-600 mb-4">Descripción del proyecto social que ayuda a la comunidad...</p>
                    <div class="bg-gray-200 rounded-full h-2 mb-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ rand(30, 90) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500">{{ rand(30, 90) }}% completado</p>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>
@endsection
