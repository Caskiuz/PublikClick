<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold">📊 Reportes y Estadísticas</h1>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <nav class="flex gap-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600 pb-2">Dashboard</a>
                    <a href="{{ route('admin.withdrawals') }}" class="text-gray-600 hover:text-purple-600 pb-2">Retiros</a>
                    <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-purple-600 pb-2">Usuarios</a>
                    <a href="{{ route('admin.reports') }}" class="text-purple-600 font-semibold border-b-2 border-purple-600 pb-2">Reportes</a>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto p-6">
            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Daily Clicks Chart -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">📈 Clicks Diarios (Últimos 30 días)</h2>
                    <canvas id="clicksChart"></canvas>
                </div>

                <!-- Rank Distribution Chart -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">🏆 Distribución por Rangos</h2>
                    <canvas id="ranksChart"></canvas>
                </div>
            </div>

            <!-- Daily Stats Table -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">📅 Estadísticas Diarias</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Clicks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ganancias Generadas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($daily_stats as $stat)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ \Carbon\Carbon::parse($stat->date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ number_format($stat->clicks) }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($stat->earnings, 0) }} COP</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Clicks Chart
        const clicksData = {!! json_encode($daily_stats->pluck('clicks')->reverse()) !!};
        const clicksDates = {!! json_encode($daily_stats->pluck('date')->reverse()->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!};

        new Chart(document.getElementById('clicksChart'), {
            type: 'line',
            data: {
                labels: clicksDates,
                datasets: [{
                    label: 'Clicks',
                    data: clicksData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Ranks Chart
        const ranksData = {!! json_encode($rank_distribution->pluck('count')) !!};
        const ranksLabels = {!! json_encode($rank_distribution->pluck('name')) !!};

        new Chart(document.getElementById('ranksChart'), {
            type: 'doughnut',
            data: {
                labels: ranksLabels,
                datasets: [{
                    data: ranksData,
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(168, 85, 247)',
                        'rgb(236, 72, 153)',
                        'rgb(251, 146, 60)',
                        'rgb(234, 179, 8)',
                        'rgb(20, 184, 166)',
                        'rgb(239, 68, 68)',
                        'rgb(156, 163, 175)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</body>
</html>
