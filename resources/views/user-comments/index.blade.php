@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">⚠️ Comentarios Pendientes</h1>
            <p class="text-yellow-100">Debes comentar sobre tus retiros para continuar usando el sistema</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if($pendingComments->count() > 0)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <p class="text-red-700 font-semibold">🔒 Tu sistema está bloqueado</p>
            <p class="text-red-600 text-sm mt-1">Tienes {{ $pendingComments->count() }} retiro(s) completado(s) que requieren tu comentario</p>
        </div>

        @foreach($pendingComments as $transaction)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">💸 Retiro Completado</h3>
                    <p class="text-sm text-gray-600">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                    ✓ Pagado
                </span>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm text-gray-600">Monto</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format(abs($transaction->amount), 0, ',', '.') }} COP</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Método de Pago</p>
                    <p class="text-lg font-semibold">{{ $transaction->paymentMethod->name ?? 'N/A' }}</p>
                </div>
                @if($transaction->payment_proof)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 mb-2">Comprobante de Pago</p>
                    <img src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Comprobante" class="max-w-md rounded-lg border">
                </div>
                @endif
            </div>

            <form action="{{ route('user-comments.store', $transaction->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">⭐ Califica tu experiencia</label>
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" required class="hidden peer">
                            <span class="text-3xl peer-checked:text-yellow-400 text-gray-300 hover:text-yellow-300 transition">⭐</span>
                        </label>
                        @endfor
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">💬 Cuéntanos sobre tu experiencia (mínimo 10 caracteres)</label>
                    <textarea name="comment" required minlength="10" maxlength="1000" rows="4" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="¿Cómo fue tu experiencia con el retiro? ¿Recibiste el pago a tiempo? ¿Recomendarías nuestro servicio?"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Tu comentario ayuda a mejorar nuestro servicio</p>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-bold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-cyan-600 transition">
                    📝 Enviar Comentario y Desbloquear Sistema
                </button>
            </form>
        </div>
        @endforeach

        @else
        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg text-center">
            <p class="text-green-700 font-semibold text-lg">✅ No tienes comentarios pendientes</p>
            <p class="text-green-600 mt-2">Tu sistema está completamente desbloqueado</p>
            <a href="{{ route('dashboard') }}" class="inline-block mt-4 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                Ir al Dashboard
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
