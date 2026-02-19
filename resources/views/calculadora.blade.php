@extends('layout')

@section('title', 'Calculadora de Ganancias')

@section('page-title', 'Calculadora de Ganancias')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Calculadora Interactiva -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-center mb-6">Calcula tus Ganancias Potenciales</h2>
        
        <div class="max-w-md mx-auto mb-8">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Número de Invitados Activos
            </label>
            <input type="number" id="referralCount" min="0" max="100" value="0"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-center text-2xl font-bold"
                   oninput="calculateEarnings()">
            <input type="range" id="referralSlider" min="0" max="100" value="0"
                   class="w-full mt-4"
                   oninput="document.getElementById('referralCount').value = this.value; calculateEarnings()">
        </div>

        <!-- Resultado -->
        <div id="result" class="hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm opacity-90">Categoría</p>
                        <p class="text-2xl font-bold" id="category">-</p>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Tus Propios Clicks</p>
                        <p class="text-2xl font-bold" id="ownClicks">$0</p>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Clicks de Invitados</p>
                        <p class="text-2xl font-bold" id="referralClicks">$0</p>
                    </div>
                </div>
                <div class="mt-6 text-center border-t border-white/30 pt-4">
                    <p class="text-sm opacity-90">Ganancias Aproximadas al Mes</p>
                    <p class="text-4xl font-bold" id="totalEarnings">$0</p>
                </div>
            </div>

            <!-- Detalles de la Categoría -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Beneficios de tu Categoría</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span id="benefit1">-</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span id="benefit2">-</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span id="benefit3">-</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span id="benefit4">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Completa de Ganancias -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center mb-6">Tabla Completa de Ganancias por Categoría</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">N° Invitados</th>
                        <th class="px-4 py-3 text-left">Categoría</th>
                        <th class="px-4 py-3 text-right">Tus Clicks</th>
                        <th class="px-4 py-3 text-right">Clicks Invitados</th>
                        <th class="px-4 py-3 text-right font-bold">Total/Mes</th>
                    </tr>
                </thead>
                <tbody id="earningsTable">
                    <!-- Se llenará con JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const earningsData = [
    {referrals: 1, category: 'JADE', own: 70000, referral: 28000, total: 98000},
    {referrals: 2, category: 'JADE', own: 70000, referral: 56000, total: 126000},
    {referrals: 3, category: 'PERLA', own: 70000, referral: 138000, total: 208000},
    {referrals: 4, category: 'PERLA', own: 70000, referral: 184000, total: 254000},
    {referrals: 5, category: 'PERLA', own: 70000, referral: 230000, total: 300000},
    {referrals: 6, category: 'ZAFIRO', own: 70000, referral: 384000, total: 454000},
    {referrals: 7, category: 'ZAFIRO', own: 70000, referral: 448000, total: 518000},
    {referrals: 8, category: 'ZAFIRO', own: 70000, referral: 512000, total: 582000},
    {referrals: 9, category: 'ZAFIRO', own: 70000, referral: 576000, total: 646000},
    {referrals: 10, category: 'RUBY', own: 70000, referral: 820000, total: 890000},
    {referrals: 11, category: 'RUBY', own: 70000, referral: 902000, total: 972000},
    {referrals: 12, category: 'RUBY', own: 70000, referral: 984000, total: 1054000},
    {referrals: 13, category: 'RUBY', own: 70000, referral: 1066000, total: 1136000},
    {referrals: 14, category: 'RUBY', own: 70000, referral: 1148000, total: 1218000},
    {referrals: 15, category: 'RUBY', own: 70000, referral: 1230000, total: 1300000},
    {referrals: 16, category: 'RUBY', own: 70000, referral: 1312000, total: 1382000},
    {referrals: 17, category: 'RUBY', own: 70000, referral: 1394000, total: 1464000},
    {referrals: 18, category: 'RUBY', own: 70000, referral: 1476000, total: 1546000},
    {referrals: 19, category: 'RUBY', own: 70000, referral: 1558000, total: 1628000},
    {referrals: 20, category: 'ESMERALDA', own: 70000, referral: 1700000, total: 1770000},
    {referrals: 21, category: 'ESMERALDA', own: 70000, referral: 1785000, total: 1855000},
    {referrals: 22, category: 'ESMERALDA', own: 70000, referral: 1870000, total: 1940000},
    {referrals: 23, category: 'ESMERALDA', own: 70000, referral: 1955000, total: 2025000},
    {referrals: 24, category: 'ESMERALDA', own: 70000, referral: 2040000, total: 2110000},
    {referrals: 25, category: 'ESMERALDA', own: 70000, referral: 2125000, total: 2195000},
    {referrals: 26, category: 'DIAMANTE', own: 70000, referral: 2210000, total: 2280000},
    {referrals: 27, category: 'DIAMANTE', own: 70000, referral: 2295000, total: 2365000},
    {referrals: 28, category: 'DIAMANTE', own: 70000, referral: 2380000, total: 2450000},
    {referrals: 29, category: 'DIAMANTE', own: 70000, referral: 2465000, total: 2535000},
    {referrals: 30, category: 'DIAMANTE', own: 70000, referral: 2550000, total: 2620000},
    {referrals: 31, category: 'DIAMANTE AZUL', own: 70000, referral: 2635000, total: 2705000},
    {referrals: 32, category: 'DIAMANTE AZUL', own: 70000, referral: 2720000, total: 2790000},
    {referrals: 33, category: 'DIAMANTE AZUL', own: 70000, referral: 2805000, total: 2875000},
    {referrals: 34, category: 'DIAMANTE AZUL', own: 70000, referral: 2890000, total: 2960000},
    {referrals: 35, category: 'DIAMANTE AZUL', own: 70000, referral: 2975000, total: 3045000},
    {referrals: 36, category: 'DIAMANTE NEGRO', own: 70000, referral: 3060000, total: 3130000},
    {referrals: 37, category: 'DIAMANTE NEGRO', own: 70000, referral: 3145000, total: 3215000},
    {referrals: 38, category: 'DIAMANTE NEGRO', own: 70000, referral: 3230000, total: 3300000},
    {referrals: 39, category: 'DIAMANTE NEGRO', own: 70000, referral: 3315000, total: 3385000},
    {referrals: 40, category: 'DIAMANTE CORONA', own: 70000, referral: 3400000, total: 3470000}
];

function formatCurrency(amount) {
    return '$' + amount.toLocaleString('es-CO');
}

function calculateEarnings() {
    const count = parseInt(document.getElementById('referralCount').value) || 0;
    document.getElementById('referralSlider').value = count;
    
    if (count === 0) {
        document.getElementById('result').classList.add('hidden');
        return;
    }
    
    document.getElementById('result').classList.remove('hidden');
    
    // Buscar datos correspondientes
    let data = earningsData.find(d => d.referrals === count);
    
    // Si no existe exactamente, buscar el rango
    if (!data) {
        data = earningsData.find(d => d.referrals >= count) || earningsData[earningsData.length - 1];
    }
    
    // Actualizar UI
    document.getElementById('category').textContent = data.category;
    document.getElementById('ownClicks').textContent = formatCurrency(data.own);
    document.getElementById('referralClicks').textContent = formatCurrency(data.referral);
    document.getElementById('totalEarnings').textContent = formatCurrency(data.total);
    
    // Beneficios según categoría
    const benefits = {
        'JADE': [
            '5 anuncios diarios ($400 c/u)',
            '4 mini-anuncios diarios',
            'Comisión $100 por click de referido',
            '1 mini-anuncio desbloqueado por referido'
        ],
        'PERLA': [
            '5 anuncios diarios ($400 c/u)',
            '4 mini-anuncios diarios',
            'Comisión $200 por click de referido',
            '2 mini-anuncios desbloqueados por referido'
        ],
        'ZAFIRO': [
            '5 anuncios diarios ($400 c/u)',
            '4 mini-anuncios diarios',
            'Comisión $300 por click de referido',
            '3 mini-anuncios desbloqueados por referido'
        ],
        'RUBY': [
            '5 anuncios diarios ($400 c/u)',
            '4 mini-anuncios diarios',
            'Comisión $400 por click de referido',
            '4 mini-anuncios desbloqueados por referido'
        ],
        'ESMERALDA': [
            '5 anuncios diarios ($400 c/u)',
            '4 mini-anuncios diarios',
            'Comisión $400 por click de referido',
            '5 mini-anuncios desbloqueados por referido'
        ],
        'DIAMANTE': [
            '5 anuncios diarios ($400 c/u)',
            '4 mini-anuncios diarios',
            'Comisión $400 por click de referido',
            '5 mini-anuncios + Retiros sin límite'
        ],
        'DIAMANTE AZUL': [
            '5 anuncios diarios ($400 c/u)',
            '4 mini-anuncios diarios',
            'Comisión $400 por click de referido',
            '5 mini-anuncios + Retiros sin límite'
        ],
        'DIAMANTE NEGRO': [
            '5 anuncios diarios ($400 c/u)',
            '4 mini-anuncios diarios',
            'Comisión $400 por click de referido',
            '5 mini-anuncios + Retiros sin límite'
        ],
        'DIAMANTE CORONA': [
            '5 anuncios diarios ($400 c/u)',
            '4 mini-anuncios diarios',
            'Comisión $400 por click de referido',
            '5 mini-anuncios + Multinivel hasta 6 niveles'
        ]
    };
    
    const categoryBenefits = benefits[data.category] || benefits['JADE'];
    document.getElementById('benefit1').textContent = categoryBenefits[0];
    document.getElementById('benefit2').textContent = categoryBenefits[1];
    document.getElementById('benefit3').textContent = categoryBenefits[2];
    document.getElementById('benefit4').textContent = categoryBenefits[3];
}

// Llenar tabla
function fillTable() {
    const tbody = document.getElementById('earningsTable');
    let html = '';
    
    earningsData.forEach(data => {
        const categoryColors = {
            'JADE': 'bg-green-100 text-green-800',
            'PERLA': 'bg-pink-100 text-pink-800',
            'ZAFIRO': 'bg-blue-100 text-blue-800',
            'RUBY': 'bg-red-100 text-red-800',
            'ESMERALDA': 'bg-emerald-100 text-emerald-800',
            'DIAMANTE': 'bg-purple-100 text-purple-800',
            'DIAMANTE AZUL': 'bg-indigo-100 text-indigo-800',
            'DIAMANTE NEGRO': 'bg-gray-800 text-white',
            'DIAMANTE CORONA': 'bg-yellow-100 text-yellow-800'
        };
        
        html += `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-semibold">${data.referrals}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs font-semibold ${categoryColors[data.category]}">
                        ${data.category}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">${formatCurrency(data.own)}</td>
                <td class="px-4 py-3 text-right">${formatCurrency(data.referral)}</td>
                <td class="px-4 py-3 text-right font-bold text-green-600">${formatCurrency(data.total)}</td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Inicializar
fillTable();
</script>
@endsection
