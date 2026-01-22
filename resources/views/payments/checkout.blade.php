@extends('layout')

@section('title', 'Checkout')
@section('page-title', 'Completar Pago')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Resumen del Pedido</h2>
            <div class="space-y-3 mb-6">
                <div class="flex justify-between">
                    <span class="text-gray-600">Paquete:</span>
                    <span class="font-semibold">{{ $package->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Precio:</span>
                    <span class="font-semibold">${{ number_format($package->price_usd, 2) }} USD</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Método de Pago:</span>
                    <span class="font-semibold">{{ $gateway->name }}</span>
                </div>
                <hr class="my-4">
                <div class="flex justify-between text-lg">
                    <span class="font-bold">Total:</span>
                    <span class="font-bold text-blue-600">${{ number_format($package->price_usd, 2) }} USD</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Instrucciones de Pago</h2>
            
            @if($gateway->type === 'stripe')
                <div class="space-y-4">
                    <p class="text-gray-600">Serás redirigido a Stripe para completar tu pago de forma segura.</p>
                    <button onclick="processStripePayment()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg">
                        Pagar con Stripe
                    </button>
                </div>
            @elseif($gateway->type === 'paypal')
                <div class="space-y-4">
                    <p class="text-gray-600">Serás redirigido a PayPal para completar tu pago.</p>
                    <button onclick="processPayPalPayment()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg">
                        Pagar con PayPal
                    </button>
                </div>
            @elseif($gateway->type === 'crypto')
                <div class="space-y-4">
                    <p class="text-gray-600 mb-4">Envía el pago a la siguiente dirección:</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Dirección de Wallet:</p>
                        <p class="font-mono text-sm break-all">{{ $gateway->config['wallet_address'] ?? 'No configurada' }}</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <strong>Importante:</strong> Después de realizar el pago, envía el comprobante de transacción al administrador.
                        </p>
                    </div>
                    <button onclick="confirmCryptoPayment()" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 rounded-lg">
                        Ya Realicé el Pago
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('payments.select-method', $package) }}" class="text-blue-600 hover:text-blue-800">
            ← Cambiar método de pago
        </a>
    </div>
</div>

<script>
    function processStripePayment() {
        Modal.info('Integración con Stripe en desarrollo. Contacta al administrador.', 'Stripe');
    }

    function processPayPalPayment() {
        Modal.info('Integración con PayPal en desarrollo. Contacta al administrador.', 'PayPal');
    }

    function confirmCryptoPayment() {
        Modal.confirm(
            '¿Confirmas que has realizado el pago? El administrador verificará tu transacción.',
            'Confirmar Pago',
            () => {
                Modal.success('Pago registrado. Espera la confirmación del administrador.', 'Pago Registrado', () => {
                    window.location.href = '{{ route("dashboard") }}';
                });
            }
        );
    }
</script>
@endsection
