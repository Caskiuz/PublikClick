@extends('layout')

@section('title', 'Recomienda y Gana')
@section('page-title', 'Recomienda y Gana')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Alerta importante -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <i class="fas fa-exclamation-triangle text-yellow-400 mr-3 mt-1"></i>
            <div>
                <p class="font-bold text-yellow-800">IMPORTANTE</p>
                <p class="text-yellow-700 text-sm">Los usuarios SOLO generan comisiones por sus invitados si tienen PAQUETE ACTIVO. Los paquetes publicitarios tienen duración de 30 días. En el momento en que tu paquete está inactivo NO recibes ninguna comisión.</p>
            </div>
        </div>
    </div>

    <!-- Tu Link de Referido -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-link text-blue-600 mr-2"></i>
            Tu Link de Referido
        </h2>
        
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Link Completo</label>
            <div class="flex">
                <input type="text" id="referralLink" readonly
                       value="{{ url('/?ref=' . $user->referral_code) }}"
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg bg-white">
                <button onclick="copyLink()" 
                        class="bg-blue-600 text-white px-6 py-3 rounded-r-lg hover:bg-blue-700 transition">
                    <i class="fas fa-copy mr-1"></i> Copiar
                </button>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tu Código de Referido</label>
            <div class="flex">
                <input type="text" id="referralCode" readonly
                       value="{{ $user->referral_code }}"
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg bg-white text-2xl font-bold text-center">
                <button onclick="copyCode()" 
                        class="bg-green-600 text-white px-6 py-3 rounded-r-lg hover:bg-green-700 transition">
                    <i class="fas fa-copy mr-1"></i> Copiar
                </button>
            </div>
        </div>
    </div>

    <!-- Compartir en Redes Sociales -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-share-alt text-purple-600 mr-2"></i>
            Compartir en Redes Sociales
        </h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="https://wa.me/?text={{ urlencode('¡Únete a PubliHazClick y gana dinero viendo anuncios! ' . url('/?ref=' . $user->referral_code)) }}" 
               target="_blank"
               class="bg-green-500 text-white p-4 rounded-lg text-center hover:bg-green-600 transition">
                <i class="fab fa-whatsapp text-3xl mb-2"></i>
                <p class="text-sm font-semibold">WhatsApp</p>
            </a>
            
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/?ref=' . $user->referral_code)) }}" 
               target="_blank"
               class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition">
                <i class="fab fa-facebook text-3xl mb-2"></i>
                <p class="text-sm font-semibold">Facebook</p>
            </a>
            
            <a href="https://twitter.com/intent/tweet?text={{ urlencode('¡Únete a PubliHazClick!') }}&url={{ urlencode(url('/?ref=' . $user->referral_code)) }}" 
               target="_blank"
               class="bg-sky-500 text-white p-4 rounded-lg text-center hover:bg-sky-600 transition">
                <i class="fab fa-twitter text-3xl mb-2"></i>
                <p class="text-sm font-semibold">Twitter</p>
            </a>
            
            <a href="https://t.me/share/url?url={{ urlencode(url('/?ref=' . $user->referral_code)) }}&text={{ urlencode('¡Únete a PubliHazClick!') }}" 
               target="_blank"
               class="bg-blue-500 text-white p-4 rounded-lg text-center hover:bg-blue-600 transition">
                <i class="fab fa-telegram text-3xl mb-2"></i>
                <p class="text-sm font-semibold">Telegram</p>
            </a>
        </div>
    </div>

    <!-- Estadísticas de Referidos -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-bar text-green-600 mr-2"></i>
            Tus Estadísticas
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-4 text-center">
                <i class="fas fa-users text-3xl mb-2"></i>
                <p class="text-3xl font-bold">{{ $user->referrals()->count() }}</p>
                <p class="text-sm">Total Referidos</p>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-4 text-center">
                <i class="fas fa-user-check text-3xl mb-2"></i>
                <p class="text-3xl font-bold">{{ $user->activeReferrals()->count() }}</p>
                <p class="text-sm">Referidos Activos</p>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg p-4 text-center">
                <i class="fas fa-crown text-3xl mb-2"></i>
                <p class="text-3xl font-bold">{{ $user->currentRank->name ?? 'Jade' }}</p>
                <p class="text-sm">Tu Categoría</p>
            </div>
        </div>
    </div>
</div>

<script>
function copyLink() {
    const link = document.getElementById('referralLink');
    link.select();
    document.execCommand('copy');
    
    Swal.fire({
        icon: 'success',
        title: '¡Copiado!',
        text: 'Link de referido copiado al portapapeles',
        timer: 2000,
        showConfirmButton: false
    });
}

function copyCode() {
    const code = document.getElementById('referralCode');
    code.select();
    document.execCommand('copy');
    
    Swal.fire({
        icon: 'success',
        title: '¡Copiado!',
        text: 'Código de referido copiado al portapapeles',
        timer: 2000,
        showConfirmButton: false
    });
}
</script>
@endsection
