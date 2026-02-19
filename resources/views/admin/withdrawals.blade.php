<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Retiros - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold">💳 Gestión de Retiros</h1>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <nav class="flex gap-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600 pb-2">Dashboard</a>
                    <a href="{{ route('admin.withdrawals') }}" class="text-purple-600 font-semibold border-b-2 border-purple-600 pb-2">Retiros</a>
                    <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-purple-600 pb-2">Usuarios</a>
                    <a href="{{ route('admin.reports') }}" class="text-gray-600 hover:text-purple-600 pb-2">Reportes</a>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex gap-4">
                    <button onclick="filterStatus('all')" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Todos</button>
                    <button onclick="filterStatus('pending')" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">Pendientes</button>
                    <button onclick="filterStatus('completed')" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Completados</button>
                    <button onclick="filterStatus('rejected')" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Rechazados</button>
                </div>
            </div>

            <!-- Withdrawals Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($withdrawals as $withdrawal)
                            <tr class="withdrawal-row" data-status="{{ $withdrawal->status }}">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $withdrawal->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $withdrawal->user->email }}</p>
                                        <p class="text-xs text-gray-500">WhatsApp: {{ $withdrawal->user->whatsapp }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-lg font-bold text-gray-800">${{ number_format(abs($withdrawal->amount), 0) }}</p>
                                    <p class="text-xs text-gray-500">COP</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold">{{ $withdrawal->payment_method ?? 'N/A' }}</p>
                                    @if($withdrawal->payment_details)
                                        <p class="text-xs text-gray-600">{{ $withdrawal->payment_details }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($withdrawal->status === 'pending')
                                        <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold">⏳ Pendiente</span>
                                    @elseif($withdrawal->status === 'completed')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">✅ Completado</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">❌ Rechazado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $withdrawal->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($withdrawal->status === 'pending')
                                        <button onclick="openApproveModal({{ $withdrawal->id }})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 mb-1">
                                            ✅ Aprobar
                                        </button>
                                        <button onclick="openRejectModal({{ $withdrawal->id }})" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                            ❌ Rechazar
                                        </button>
                                    @elseif($withdrawal->status === 'completed' && $withdrawal->proof_image)
                                        <button onclick="viewProof('{{ asset('storage/' . $withdrawal->proof_image) }}')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                            👁️ Ver comprobante
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $withdrawals->links() }}
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h3 class="text-2xl font-bold mb-4">✅ Aprobar Retiro</h3>
            <form id="approveForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Comprobante de Pago *</label>
                    <input type="file" name="proof_image" required accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                    <p class="text-sm text-gray-500 mt-1">Sube captura del pago realizado</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Notas (Opcional)</label>
                    <textarea name="admin_notes" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600">
                        Aprobar y Subir
                    </button>
                    <button type="button" onclick="closeApproveModal()" class="flex-1 bg-gray-300 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h3 class="text-2xl font-bold mb-4">❌ Rechazar Retiro</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Motivo del Rechazo *</label>
                    <textarea name="admin_notes" rows="4" required class="w-full px-4 py-2 border rounded-lg" placeholder="Explica por qué se rechaza el retiro..."></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-600">
                        Rechazar
                    </button>
                    <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-300 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(id) {
            document.getElementById('approveForm').action = `/admin/withdrawals/${id}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `/admin/withdrawals/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        function viewProof(url) {
            window.open(url, '_blank', 'width=800,height=600');
        }

        function filterStatus(status) {
            const rows = document.querySelectorAll('.withdrawal-row');
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
