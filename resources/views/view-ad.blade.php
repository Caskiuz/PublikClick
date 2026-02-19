@extends('layout')

@section('title', 'Ver Anuncio')
@section('page-title', 'Visualizando Anuncio')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Temporizador -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold">Tiempo restante</h3>
                <span id="timer" class="text-3xl font-bold text-blue-600">{{ $duration }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div id="progress" class="bg-blue-600 h-4 rounded-full transition-all duration-1000" style="width: 0%"></div>
            </div>
        </div>

        <!-- Anuncio -->
        <div id="adContainer" class="mb-6">
            <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
                @if($ad->image_url)
                    <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="max-w-full max-h-full object-contain">
                @else
                    <div class="text-center p-8">
                        <h2 class="text-2xl font-bold mb-2">{{ $ad->title }}</h2>
                        <p class="text-gray-600">{{ $ad->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Validador CAPTCHA (oculto inicialmente) -->
        <div id="captchaContainer" class="hidden">
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4 text-center">¡Validación Final!</h3>
                <p id="captchaQuestion" class="text-center mb-6 text-lg"></p>
                <div id="captchaOptions" class="grid grid-cols-3 gap-4"></div>
            </div>
        </div>

        <!-- Alerta de pestaña inactiva -->
        <div id="tabAlert" class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
            <p class="font-bold">⚠️ Contador Pausado</p>
            <p>Regresa a esta pestaña para continuar viendo el anuncio</p>
        </div>
    </div>
</div>

<script>
const duration = {{ $duration }};
const adType = '{{ $adType }}';
const adId = {{ $ad->id }};
let timeLeft = duration;
let isPaused = false;
let timerInterval;

// Detectar cambio de pestaña
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        isPaused = true;
        document.getElementById('tabAlert').classList.remove('hidden');
    } else {
        isPaused = false;
        document.getElementById('tabAlert').classList.add('hidden');
    }
});

// Iniciar temporizador
function startTimer() {
    timerInterval = setInterval(() => {
        if (!isPaused && timeLeft > 0) {
            timeLeft--;
            updateDisplay();
            
            if (timeLeft === 0) {
                clearInterval(timerInterval);
                showCaptcha();
            }
        }
    }, 1000);
}

function updateDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    document.getElementById('timer').textContent = 
        `${minutes}:${seconds.toString().padStart(2, '0')}`;
    
    const progress = ((duration - timeLeft) / duration) * 100;
    document.getElementById('progress').style.width = progress + '%';
}

function showCaptcha() {
    document.getElementById('adContainer').classList.add('hidden');
    document.getElementById('captchaContainer').classList.remove('hidden');
    
    const captchas = [
        {
            question: 'Selecciona el micrófono de color AZUL',
            options: ['🎤', '🎤', '🎤'],
            colors: ['red', 'blue', 'green'],
            correct: 1
        },
        {
            question: 'Selecciona la estrella de color AMARILLO',
            options: ['⭐', '⭐', '⭐'],
            colors: ['blue', 'yellow', 'red'],
            correct: 1
        },
        {
            question: 'Selecciona el corazón de color ROJO',
            options: ['❤️', '❤️', '❤️'],
            colors: ['red', 'blue', 'green'],
            correct: 0
        }
    ];
    
    const captcha = captchas[Math.floor(Math.random() * captchas.length)];
    document.getElementById('captchaQuestion').textContent = captcha.question;
    
    const optionsContainer = document.getElementById('captchaOptions');
    optionsContainer.innerHTML = '';
    
    captcha.options.forEach((icon, index) => {
        const button = document.createElement('button');
        button.className = 'text-6xl p-6 rounded-lg hover:bg-blue-100 transition-colors border-2 border-gray-300';
        button.style.color = captcha.colors[index];
        button.textContent = icon;
        button.onclick = () => validateCaptcha(index === captcha.correct);
        optionsContainer.appendChild(button);
    });
}

async function validateCaptcha(isCorrect) {
    if (isCorrect) {
        try {
            const response = await fetch(`/clicks/${adType}/${adId}/validate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ view_time: duration })
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert(`¡Has sumado ${data.earnings}!`);
                window.location.href = '/anuncios';
            }
        } catch (error) {
            alert('Error al validar');
        }
    } else {
        alert('❌ Respuesta incorrecta. El contador se reiniciará.');
        location.reload();
    }
}

startTimer();
</script>
@endsection
