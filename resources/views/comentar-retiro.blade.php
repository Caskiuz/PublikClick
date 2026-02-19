@extends('layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4">
    <div class="max-w-2xl w-full">
        <div class="bg-red-100 border-l-4 border-red-500 p-6 mb-6 rounded">
            <div class="flex items-center mb-4">
                <div class="text-4xl mr-4">🔒</div>
                <div>
                    <h2 class="text-2xl font-bold text-red-800">Sistema Bloqueado</h2>
                    <p class="text-red-700">Debes comentar tu comprobante de pago para continuar</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl p-8">
            <h3 class="text-xl font-bold mb-4">💰 Tu Retiro fue Procesado</h3>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Monto:</p>
                        <p class="font-bold text-lg">${{ number_format($transaction->amount, 0, ',', '.') }} COP</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Fecha:</p>
                        <p class="font-bold">{{ $transaction->processed_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Método:</p>
                        <p class="font-bold capitalize">{{ str_replace('_', ' ', $transaction->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Estado:</p>
                        <p class="font-bold text-green-600">✓ Pago Exitoso</p>
                    </div>
                </div>
            </div>

            @if($transaction->payment_details && isset($transaction->payment_details['proof_url']))
            <div class="mb-6">
                <p class="font-semibold mb-2">Comprobante de Pago:</p>
                <img src="{{ $transaction->payment_details['proof_url'] }}" alt="Comprobante" class="w-full rounded-lg border">
            </div>
            @endif

            <form action="{{ route('testimonio.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                
                <div>
                    <label class="block text-sm font-bold mb-2">
                        ✍️ Deja tu Testimonio (Obligatorio)
                    </label>
                    <textarea 
                        name="comment" 
                        required 
                        minlength="10" 
                        maxlength="1000"
                        rows="6"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Comparte tu experiencia con PubliHazClick... ¿Cómo fue el proceso? ¿Recibiste tu pago a tiempo? ¿Recomendarías la plataforma?"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">Mínimo 10 caracteres, máximo 1000</p>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <p class="text-sm text-blue-700">
                        <strong>ℹ️ Importante:</strong> Tu testimonio será publicado en la sección de testimonios para que otros usuarios conozcan tu experiencia. No podrás acceder al sistema hasta que completes este paso.
                    </p>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-lg">
                    ✓ Enviar Testimonio y Desbloquear Sistema
                </button>
            </form>
        </div>

        <div class="mt-6 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
