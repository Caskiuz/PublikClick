@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">🎬 Mini-Anuncios</h1>
            <p class="text-green-100">Visualiza 60 segundos y gana ${{ number_format($earnings, 0, ',', '.') }} COP por click</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-lg mb-2">Mini-Anuncios del Paquete</h3>
                <p class="text-4xl font-bold text-green-600">{{ $availableMini }}</p>
                <p class="text-sm text-gray-600 mt-2">✅ Acumulables por 30 días</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-lg mb-2">Mini-Anuncios Desbloqueados</h3>
                <p class="text-4xl font-bold text-purple-600">{{ $availableUnlocked }}</p>
                <p class="text-sm text-gray-600 mt-2">🎁 Por tus referidos activos</p>
            </div>
        </div>

        @if($availableMini > 0 || $availableUnlocked > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold mb-2">Mini-Anuncio</h2>
                <p class="text-gray-600">60 segundos de visualización</p>
            </div>

            <div class="bg-gradient-to-br from-green-100 to-teal-100 rounded-lg p-8 mb-6 min-h-[250px] flex items-center justify-center">
                <div class="text-center">
                    <div class="text-5xl mb-4">💰</div>
                    <h3 class="text-xl font-bold text-gray-800">Anuncio Rápido</h3>
                </div>
            </div>

            <div class="text-center mb-6">
                <div class="inline-block bg-green-600 text-white rounded-full px-8 py-4">
                    <p class="text-sm mb-1">Tiempo Restante</p>
                    <p id="timer" class="text-4xl font-bold">60</p>
                </div>
                <p id="status" class="mt-4 text-gray-600">Listo para iniciar...</p>
            </div>

            <div id="captchaContainer" class="hidden">
                <div class="bg-gray-50 rounded-lg p-6 max-w-md mx-auto">
                    <h3 class="text-xl font-bold mb-4 text-center">Verifica que eres humano</h3>
                    <p id="captchaQuestion" class="text-lg mb-4 text-center"></p>
                    <div id="captchaOptions" class="grid grid-cols-2 gap-3"></div>
                </div>
            </div>

            <div class="text-center space-x-4">
                @if($availableMini > 0)
                <button id="startMini" onclick="startAd('mini')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg">
                    ▶️ Mini-Anuncio Paquete
                </button>
                @endif
                @if($availableUnlocked > 0)
                <button id="startUnlocked" onclick="startAd('mini_unlocked')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg">
                    ▶️ Mini-Anuncio Desbloqueado
                </button>
                @endif
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-2xl font-bold mb-2">No hay mini-anuncios disponibles</h3>
            <p class="text-gray-600">Se recargan diariamente a las 12:00 AM</p>
        </div>
        @endif
    </div>
</div>

<script>
let timeLeft = 60;
let timerInterval = null;
let isTabActive = true;
let currentAdType = null;

document.addEventListener('visibilitychange', function() {
    isTabActive = !document.hidden;
    if (!isTabActive && timerInterval) {
        document.getElementById('status').textContent = '⏸️ Pausado';
        document.getElementById('status').classList.add('text-red-600');
    } else if (isTabActive && timerInterval) {
        document.getElementById('status').textContent = 'Visualizando...';
        document.getElementById('status').classList.remove('text-red-600');
    }
});

function startAd(type) {
    currentAdType = type;
    document.getElementById('startMini')?.classList.add('hidden');
    document.getElementById('startUnlocked')?.classList.add('hidden');
    startTimer();
}

function startTimer() {
    timerInterval = setInterval(() => {
        if (isTabActive && timeLeft > 0) {
            timeLeft--;
            document.getElementById('timer').textContent = timeLeft;
            if (timeLeft === 0) {
                clearInterval(timerInterval);
                showCaptcha();
            }
        }
    }, 1000);
}

async function showCaptcha() {
    document.getElementById('status').textContent = '✅ Completado - Resuelve el CAPTCHA';
    const response = await fetch('/api/captcha/generate');
    const captchaData = await response.json();
    document.getElementById('captchaQuestion').textContent = captchaData.question;
    const optionsContainer = document.getElementById('captchaOptions');
    optionsContainer.innerHTML = '';
    captchaData.options.forEach(option => {
        const btn = document.createElement('button');
        btn.textContent = option;
        btn.className = 'bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg';
        btn.onclick = () => submitCaptcha(option);
        optionsContainer.appendChild(btn);
    });
    document.getElementById('captchaContainer').classList.remove('hidden');
}

async function submitCaptcha(answer) {
    const response = await fetch('/ads/process-click', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({ad_type: currentAdType, captcha_answer: answer})
    });
    const result = await response.json();
    alert(result.success ? '✅ ' + result.message : '❌ ' + result.message);
    window.location.reload();
}
</script>
@endsection
