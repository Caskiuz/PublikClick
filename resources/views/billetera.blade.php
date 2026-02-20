@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">💰 Mi Billetera</h1>
            <p class="text-green-100">Gestiona tus ganancias y retiros</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">{{ session('error') }}</div>
        @endif

        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-700">💵 Acumulado de Retiro</h3>
                    <span class="text-green-600 text-2xl">💰</span>
                </div>
                <p class="text-4xl font-bold text-green-600 mb-2">${{ number_format($withdrawalWallet->balance ?? 0, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600">COP</p>
                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Total Ganado:</span>
                        <span class="font-semibold">${{ number_format($withdrawalWallet->total_earned ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Retirado:</span>
                        <span class="font-semibold">${{ number_format($withdrawalWallet->total_withdrawn ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-700">🎁 Acumulado de Donaciones</h3>
                    <span class="text-purple-600 text-2xl">❤️</span>
                </div>
                <p class="text-4xl font-bold text-purple-600 mb-2">${{ number_format($donationWallet->balance ?? 0, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600">COP</p>
                <div class="mt-4 pt-4 border-t">
                    <p class="text-xs text-gray-500">$10 COP por cada anuncio principal completado</p>
                    <p class="text-xs text-gray-500 mt-1">Destinado a proyectos comunitarios</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold mb-4">💸 Solicitar Retiro</h3>
            
            @if($canWithdraw['can'])
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                <p class="text-sm text-blue-700">✅ Cumples los requisitos para retirar</p>
                <p class="text-sm text-blue-700">Monto mínimo: ${{ number_format($minimumWithdrawal['cop'], 0, ',', '.') }} COP</p>
            </div>

            <form action="{{ route('wallet.withdraw') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Monto a Retirar (COP)</label>
                        <input type="number" name="amount" required min="{{ $minimumWithdrawal['cop'] }}" max="{{ $withdrawalWallet->balance }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Ej: 150000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Método de Pago</label>
                        <select name="payment_method" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Seleccionar...</option>
                            @foreach($paymentMethods as $method)
                            <option value="{{ $method->slug }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1" id="payment-instructions"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Titular de la Cuenta</label>
                        <input type="text" name="account_holder" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Nombre completo">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Número de Cuenta</label>
                        <input type="text" name="account_number" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Número o correo">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Documento de Identidad</label>
                        <input type="text" name="document_number" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" placeholder="CC, DNI, Pasaporte">
                    </div>
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg">
                    💸 Solicitar Retiro
                </button>
            </form>
            @else
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <p class="text-red-700 font-semibold">❌ {{ $canWithdraw['reason'] }}</p>
                <p class="text-sm text-red-600 mt-2">Requisitos para retirar:</p>
                <ul class="text-sm text-red-600 mt-1 ml-4 list-disc">
                    <li>Paquete activo vigente</li>
                    <li>Mínimo 1 invitado activo</li>
                    <li>30 días desde último retiro</li>
                    <li>Alcanzar monto mínimo de tu categoría</li>
                </ul>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">📊 Transacciones Recientes</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Monto</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $transaction->description }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($transaction->type == 'credit')
                                <span class="text-green-600">➕ Ingreso</span>
                                @else
                                <span class="text-red-600">➖ Retiro</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold">${{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($transaction->status == 'completed')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">✓ Completado</span>
                                @elseif($transaction->status == 'pending')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">⏳ Pendiente</span>
                                @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">✗ Rechazado</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">No hay transacciones aún</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
