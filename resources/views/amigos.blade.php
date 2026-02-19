@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-gradient-to-r from-pink-600 to-rose-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">👥 Comunidad de Amigos</h1>
            <p class="text-pink-100">Conecta con otros miembros de PubliClick</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-5xl mb-3">👥</div>
                <p class="text-3xl font-bold text-blue-600">{{ Auth::user()->referrals->count() }}</p>
                <p class="text-sm text-gray-600 mt-1">Tus Invitados</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-5xl mb-3">✅</div>
                <p class="text-3xl font-bold text-green-600">{{ Auth::user()->activeReferrals->count() }}</p>
                <p class="text-sm text-gray-600 mt-1">Invitados Activos</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-5xl mb-3">🌟</div>
                <p class="text-3xl font-bold text-purple-600">{{ Auth::user()->currentRank->name ?? 'Jade' }}</p>
                <p class="text-sm text-gray-600 mt-1">Tu Categoría</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold mb-4">🎯 Tus Invitados Directos</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse(Auth::user()->referrals as $referral)
                        <tr>
                            <td class="px-4 py-3 text-sm font-semibold">{{ $referral->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $referral->email }}</td>
                            <td class="px-4 py-3 text-sm">{{ $referral->currentRank->name ?? 'Jade' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($referral->activePackage)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">✓ Activo</span>
                                @else
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $referral->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <div class="text-5xl mb-3">📭</div>
                                <p>Aún no has invitado a nadie</p>
                                <a href="{{ route('recomienda-gana') }}" class="inline-block mt-3 bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-6 rounded-lg">
                                    Invitar Amigos
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <h4 class="font-bold text-blue-800 mb-2">💡 Beneficios de Invitar Amigos</h4>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Gana mega-anuncios cuando tus invitados compran paquetes</li>
                <li>• Recibe comisiones por cada click que hagan tus referidos</li>
                <li>• Desbloquea mini-anuncios adicionales según tu categoría</li>
                <li>• Sube de categoría y aumenta tus ganancias</li>
            </ul>
        </div>
    </div>
</div>
@endsection
