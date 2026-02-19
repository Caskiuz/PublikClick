@extends('layout')

@section('title', 'Mi Líder')
@section('page-title', 'Mi Líder')

@section('content')
<div class="max-w-4xl mx-auto">
    @if($lider)
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="text-center mb-6">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                {{ substr($lider->name, 0, 1) }}
            </div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $lider->name }}</h2>
            <p class="text-gray-600">Tu Líder en PubliHazClick</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-envelope text-blue-600 mr-2"></i>
                    <span class="font-semibold text-gray-700">Correo Electrónico</span>
                </div>
                <p class="text-gray-800">{{ $lider->email }}</p>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fab fa-whatsapp text-green-600 mr-2"></i>
                    <span class="font-semibold text-gray-700">WhatsApp</span>
                </div>
                <p class="text-gray-800">{{ $lider->whatsapp ?? 'No disponible' }}</p>
                @if($lider->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lider->whatsapp) }}" 
                   target="_blank"
                   class="inline-block mt-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                    <i class="fab fa-whatsapp mr-1"></i> Contactar
                </a>
                @endif
            </div>

            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-crown text-purple-600 mr-2"></i>
                    <span class="font-semibold text-gray-700">Categoría</span>
                </div>
                <p class="text-gray-800">{{ $lider->currentRank->name ?? 'Sin categoría' }}</p>
            </div>

            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-users text-yellow-600 mr-2"></i>
                    <span class="font-semibold text-gray-700">Invitados Activos</span>
                </div>
                <p class="text-gray-800">{{ $lider->activeReferrals()->count() }}</p>
            </div>
        </div>

        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 text-center">
                <i class="fas fa-info-circle mr-1"></i>
                Este es el líder que te invitó a PubliHazClick. Puedes contactarlo para recibir ayuda y orientación.
            </p>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-lg p-6 text-center">
        <i class="fas fa-user-slash text-gray-400 text-6xl mb-4"></i>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">No tienes un líder asignado</h2>
        <p class="text-gray-600">Te registraste sin un código de referido.</p>
    </div>
    @endif
</div>
@endsection
