@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-3xl font-bold mb-2">📺 Anuncios Principales</h1>
            <p class="text-blue-100">Visualiza 90 segundos y gana ${{ number_format($earnings, 0, ',', '.') }} COP por click</p>
        </div>

        <!-- Disponibles -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Anuncios Disponibles Hoy</p>
                    <p class="text-4xl font-bold text-blue-600">{{ $available }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Ganancia por Click</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($earnings, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <p class="text-sm text-yellow-700">⚠️ Los anuncios principales NO se acumulan. Se renuevan cada día a las 12:00 AM.</p>
            </div>
        </div>

        <!-- Anuncio -->
        @if($available > 0)
        <div id="adContainer" class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold mb-2">Anuncio Publicitario</h2>
                <p class="text-gray-600">Mantén esta pestaña activa durante 90 segundos</p>
            </div>

            <!-- Área del anuncio -->
            <div class="bg-gradient-to-br from-purple-100 to-blue-100 rounded-lg p-8 mb-6 min-h-[300px] flex items-center justify-center">
                <div class="text-center">
                    <div class="text-6xl mb-4">🎯</div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">PubliHazClick</h3>
                    <p class="text-gray-600">Tu plataforma de publicidad PTC</p>
                </div>
            </div>

            <!-- Temporizador -->
            <div class="text-center mb-6">
                <div class="inline-block bg-blue-600 text-white rounded-full px-8 py-4">
                    <p class="text-sm mb-1">Tiempo Restante</p>
                    <p id="timer" class="text-4xl font-bold">90</p>
                </div>
                <p id="status" class="mt-4 text-gray-600">Visualizando anuncio...</p>
            </div>

            <!-- CAPTCHA (oculto inicialmente) -->
            <div id="captchaContainer" class="hidden">
                <div class="bg-gray-50 rounded-lg p-6 max-w-md mx-auto">
                    <h3 class="text-xl font-bold mb-4 text-center">Verifica que eres humano</h3>
                    <p id="captchaQuestion" class="text-lg mb-4 text-center"></p>
                    <div id="captchaOptions" class="grid grid-cols-2 gap-3"></div>
                </div>
            </div>

            <!-- Botón de inicio -->
            <div class="text-center">
                <button id="startBtn" onclick="startAd()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                    ▶️ Iniciar Anuncio
                </button>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">😴</div>
            <h3 class="text-2xl font-bold mb-2">No hay anuncios disponibles</h3>
            <p class="text-gray-600">Vuelve mañana a las 12:00 AM para nuevos anuncios</p>
        </div>
        @endif
    </div>
</div>

<script>
let timeLeft = 90;
let timerInterval = null;
let isTabActive = true;
let captchaData = null;

document.addEventListener('visibilitychange', function() {
    isTabActive = !document.hidden;
    if (!isTabActive && timerInterval) {
        document.getElementById('status').textContent = '⏸️ Pausado - Regresa a esta pestaña';
        document.getElementById('status').classList.add('text-red-600');
    } else if (isTabActive && timerInterval) {
        document.getElementById('status').textContent = 'Visualizando anuncio...';
        document.getElementById('status').classList.remove('text-red-600');
    }
});

function startAd() {
    document.getElementById('startBtn').classList.add('hidden');
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
    captchaData = await response.json();
    
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
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            ad_type: 'principal',
            captcha_answer: answer
        })
    });
    
    const result = await response.json();
    
    if (result.success) {
        alert('✅ ' + result.message);
        window.location.reload();
    } else {
        alert('❌ ' + result.message + '\n\nEl contador se reiniciará.');
        window.location.reload();
    }
}
</script>
@endsection
