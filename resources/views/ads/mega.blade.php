@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">⭐ Mega-Anuncios</h1>
            <p class="text-yellow-100">Visualiza 120 segundos y gana $2,000 COP por click</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Mega-Anuncios Disponibles</p>
                    <p class="text-5xl font-bold text-orange-600">{{ $available }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Ganancia por Click</p>
                    <p class="text-3xl font-bold text-green-600">$2,000</p>
                </div>
            </div>
            <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4">
                <p class="text-sm text-blue-700">🎁 Los mega-anuncios se otorgan cuando tus referidos compran paquetes</p>
                <p class="text-sm text-blue-700 mt-1">✅ Acumulables por 30 días</p>
            </div>
        </div>

        @if($available > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold mb-2">🎯 Mega-Anuncio Premium</h2>
                <p class="text-gray-600">120 segundos de visualización</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-100 via-orange-100 to-red-100 rounded-lg p-12 mb-6 min-h-[350px] flex items-center justify-center">
                <div class="text-center">
                    <div class="text-7xl mb-4">💎</div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-2">Anuncio Premium</h3>
                    <p class="text-xl text-gray-600">$2,000 COP de ganancia</p>
                </div>
            </div>

            <div class="text-center mb-6">
                <div class="inline-block bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-full px-10 py-5 shadow-lg">
                    <p class="text-sm mb-1">Tiempo Restante</p>
                    <p id="timer" class="text-5xl font-bold">120</p>
                </div>
                <p id="status" class="mt-4 text-gray-600 text-lg">Listo para iniciar...</p>
            </div>

            <div id="captchaContainer" class="hidden">
                <div class="bg-gray-50 rounded-lg p-6 max-w-md mx-auto">
                    <h3 class="text-xl font-bold mb-4 text-center">Verifica que eres humano</h3>
                    <p id="captchaQuestion" class="text-lg mb-4 text-center"></p>
                    <div id="captchaOptions" class="grid grid-cols-2 gap-3"></div>
                </div>
            </div>

            <div class="text-center">
                <button id="startBtn" onclick="startAd()" class="bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white font-bold py-4 px-10 rounded-lg text-xl shadow-lg">
                    ▶️ Iniciar Mega-Anuncio
                </button>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">🎁</div>
            <h3 class="text-2xl font-bold mb-2">No hay mega-anuncios disponibles</h3>
            <p class="text-gray-600 mb-4">Los mega-anuncios se otorgan cuando tus referidos directos compran o recompran paquetes</p>
            <div class="bg-gray-50 rounded-lg p-4 max-w-md mx-auto">
                <p class="text-sm text-gray-700 font-semibold mb-2">Cantidad según paquete del referido:</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Paquete $25: 5 mega-anuncios</li>
                    <li>• Paquete $50: 10 mega-anuncios</li>
                    <li>• Paquete $100: 20 mega-anuncios</li>
                    <li>• Paquete $150: 30 mega-anuncios</li>
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
let timeLeft = 120;
let timerInterval = null;
let isTabActive = true;

document.addEventListener('visibilitychange', function() {
    isTabActive = !document.hidden;
    if (!isTabActive && timerInterval) {
        document.getElementById('status').textContent = '⏸️ Pausado - Regresa a esta pestaña';
        document.getElementById('status').classList.add('text-red-600');
    } else if (isTabActive && timerInterval) {
        document.getElementById('status').textContent = 'Visualizando mega-anuncio...';
        document.getElementById('status').classList.remove('text-red-600');
    }
});

function startAd() {
    document.getElementById('startBtn').classList.add('hidden');
    document.getElementById('status').textContent = 'Visualizando mega-anuncio...';
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
    document.getElementById('status').textContent = '✅ Tiempo completado - Resuelve el CAPTCHA';
    document.getElementById('status').classList.add('text-green-600');
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
        body: JSON.stringify({ad_type: 'mega', captcha_answer: answer})
    });
    const result = await response.json();
    alert(result.success ? '✅ ' + result.message : '❌ ' + result.message);
    window.location.reload();
}
</script>
@endsection
