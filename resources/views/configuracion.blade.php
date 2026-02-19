@extends('layout')

@section('title', 'Configuración del Sistema')
@section('page-title', 'Configuración del Sistema')

@section('content')
<div class="space-y-6">
    @if(Auth::user()->is_admin)
    <!-- Admin Panel -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <h3 class="text-2xl font-bold mb-4 flex items-center">
            <i class="fas fa-cog mr-3"></i>
            Configuración del Sistema
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.payment-gateways.index') }}" class="bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur rounded-lg p-4 transition-all transform hover:scale-105">
                <i class="fas fa-credit-card text-4xl mb-3"></i>
                <h4 class="font-bold text-lg">Métodos de Pago</h4>
                <p class="text-sm text-purple-100 mt-1">Configurar pasarelas</p>
            </a>
            <a href="{{ route('admin.banners.index') }}" class="bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur rounded-lg p-4 transition-all transform hover:scale-105">
                <i class="fas fa-ad text-4xl mb-3"></i>
                <h4 class="font-bold text-lg">Banners</h4>
                <p class="text-sm text-purple-100 mt-1">Gestionar anuncios</p>
            </a>
            <a href="{{ route('admin.withdrawals.management') }}" class="bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur rounded-lg p-4 transition-all transform hover:scale-105">
                <i class="fas fa-money-check-alt text-4xl mb-3"></i>
                <h4 class="font-bold text-lg">Retiros</h4>
                <p class="text-sm text-purple-100 mt-1">Aprobar/rechazar</p>
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur rounded-lg p-4 transition-all transform hover:scale-105">
                <i class="fas fa-users-cog text-4xl mb-3"></i>
                <h4 class="font-bold text-lg">Usuarios</h4>
                <p class="text-sm text-purple-100 mt-1">Gestionar cuentas</p>
            </a>
        </div>
    </div>
    @else
    <script>window.location.href = "{{ route('dashboard') }}";</script>
    @endif
</div>
@endsection
