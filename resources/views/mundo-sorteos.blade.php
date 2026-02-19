@extends('layout')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">🎁 Mundo Sorteos</h1>
            <p class="text-green-100">Participa en sorteos exclusivos de la comunidad</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 text-center mb-6">
            <div class="text-8xl mb-4">🎉</div>
            <h2 class="text-3xl font-bold mb-4">¡Únete a Nuestro Grupo de WhatsApp!</h2>
            <p class="text-gray-600 mb-6">Entérate de todos los sorteos, promociones y noticias exclusivas</p>
            <a href="{{ $whatsappLink }}" target="_blank" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-lg text-lg">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Unirse al Grupo de WhatsApp
            </a>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="text-5xl mb-3 text-center">🎯</div>
                <h3 class="text-xl font-bold text-center mb-2">Sorteos Semanales</h3>
                <p class="text-gray-600 text-center text-sm">Participa en sorteos de premios en efectivo cada semana</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="text-5xl mb-3 text-center">💰</div>
                <h3 class="text-xl font-bold text-center mb-2">Premios Especiales</h3>
                <p class="text-gray-600 text-center text-sm">Bonos y premios exclusivos para miembros activos</p>
            </div>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <h4 class="font-bold text-yellow-800 mb-2">⚡ Próximos Sorteos</h4>
            <ul class="text-sm text-yellow-700 space-y-2">
                <li>🎁 <strong>Sorteo Semanal:</strong> $100,000 COP - Viernes 8:00 PM</li>
                <li>💎 <strong>Sorteo Mensual:</strong> $500,000 COP - Último día del mes</li>
                <li>🏆 <strong>Gran Sorteo Trimestral:</strong> $2,000,000 COP - Cada 3 meses</li>
            </ul>
        </div>
    </div>
</div>
@endsection
