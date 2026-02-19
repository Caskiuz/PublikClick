@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">💬 Testimonios</h1>
            <p class="text-yellow-100">Lo que dicen nuestros usuarios</p>
        </div>

        <div class="grid md:grid-cols-1 gap-6">
            @forelse($testimonios as $testimonio)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-start mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mr-4">
                        {{ substr($testimonio->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="font-bold text-lg">{{ $testimonio->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $testimonio->commented_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Retiro Exitoso</p>
                                <p class="text-lg font-bold text-green-600">${{ number_format($testimonio->amount, 0, ',', '.') }} COP</p>
                            </div>
                        </div>
                        <div class="text-yellow-500 mb-2">⭐⭐⭐⭐⭐</div>
                        <p class="text-gray-700">{{ $testimonio->user_comment }}</p>
                        
                        @if($testimonio->payment_details && isset($testimonio->payment_details['proof_url']))
                        <div class="mt-4">
                            <img src="{{ $testimonio->payment_details['proof_url'] }}" alt="Comprobante" class="max-w-sm rounded-lg border">
                        </div>
                        @endif
                        
                        <div class="mt-4 flex items-center space-x-4">
                            <button onclick="translateComment({{ $testimonio->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                🌐 Traducir
                            </button>
                            <span class="text-gray-400">•</span>
                            <span class="text-sm text-gray-500">Método: {{ ucfirst(str_replace('_', ' ', $testimonio->payment_method)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">📭</div>
                <p class="text-gray-500 text-lg">Aún no hay testimonios publicados</p>
            </div>
            @endforelse
        </div>

        @if($testimonios->hasPages())
        <div class="mt-6">
            {{ $testimonios->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function translateComment(id) {
    // Integración con Google Translate API
    alert('Función de traducción en desarrollo');
}
</script>
@endsection
