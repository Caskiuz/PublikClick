@extends('layout')

@section('title', 'Gestión de Retiros')
@section('page-title', 'Gestión de Retiros')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-money-check-alt mr-2 text-purple-600"></i>
            Gestión de Retiros
        </h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pendientes</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['pending'] }}</h3>
                </div>
                <i class="fas fa-clock text-4xl opacity-30"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Aprobados</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['approved'] }}</h3>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-30"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Rechazados</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['rejected'] }}</h3>
                </div>
                <i class="fas fa-times-circle text-4xl opacity-30"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Pagado</p>
                    <h3 class="text-2xl font-bold mt-2">{{ formatCurrency($stats['total_amount'], 'COP') }}</h3>
                </div>
                <i class="fas fa-dollar-sign text-4xl opacity-30"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nequi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($withdrawals as $w)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $w->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $w->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $w->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ formatCurrency($w->amount, 'COP') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $w->payment_method ?? 'Nequi' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $w->user->nequi_phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $w->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($w->status === 'pending')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Pendiente
                                    </span>
                                @elseif($w->status === 'completed')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Aprobado
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Rechazado
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($w->status === 'pending')
                                    <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-xs font-semibold transition-colors mr-2">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                    </form>
                                    <button onclick="rejectWithdrawal({{ $w->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-semibold transition-colors">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs">Procesado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 opacity-30"></i>
                                <p>No hay retiros registrados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>

<script>
function rejectWithdrawal(id) {
    Swal.fire({
        title: 'Rechazar Retiro',
        input: 'textarea',
        inputLabel: 'Motivo del rechazo',
        inputPlaceholder: 'Escribe el motivo...',
        showCancelButton: true,
        confirmButtonText: 'Rechazar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/withdrawals/' + id + '/reject';
            form.innerHTML = '@csrf<input type="hidden" name="reason" value="' + result.value + '">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
