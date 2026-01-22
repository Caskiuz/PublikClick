<div id="globalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div id="modalIcon" class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4">
                <svg id="successIcon" class="hidden h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <svg id="errorIcon" class="hidden h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <svg id="warningIcon" class="hidden h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <svg id="infoIcon" class="hidden h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 id="modalTitle" class="text-lg leading-6 font-medium text-gray-900 text-center mb-2"></h3>
            <p id="modalMessage" class="text-sm text-gray-500 text-center mb-4"></p>
            <div id="modalButtons" class="flex gap-3 justify-center">
                <button id="modalConfirm" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none">
                    Aceptar
                </button>
                <button id="modalCancel" class="hidden px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const Modal = {
    show(options) {
        const modal = document.getElementById('globalModal');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');
        const icon = document.getElementById('modalIcon');
        const confirmBtn = document.getElementById('modalConfirm');
        const cancelBtn = document.getElementById('modalCancel');
        
        document.querySelectorAll('#modalIcon svg').forEach(svg => svg.classList.add('hidden'));
        
        const type = options.type || 'info';
        const colors = {success: 'green', error: 'red', warning: 'yellow', info: 'blue'};
        icon.className = `mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-${colors[type]}-100`;
        document.getElementById(`${type}Icon`).classList.remove('hidden');
        
        title.textContent = options.title || '';
        message.textContent = options.message || '';
        
        confirmBtn.textContent = options.confirmText || 'Aceptar';
        const btnColors = {
            error: 'bg-red-600 hover:bg-red-700',
            success: 'bg-green-600 hover:bg-green-700',
            warning: 'bg-yellow-600 hover:bg-yellow-700',
            info: 'bg-blue-600 hover:bg-blue-700'
        };
        confirmBtn.className = `px-4 py-2 text-white text-base font-medium rounded-md shadow-sm focus:outline-none ${btnColors[type]}`;
        
        if (options.showCancel) {
            cancelBtn.classList.remove('hidden');
            cancelBtn.textContent = options.cancelText || 'Cancelar';
        } else {
            cancelBtn.classList.add('hidden');
        }
        
        confirmBtn.onclick = () => {
            this.hide();
            if (options.onConfirm) options.onConfirm();
        };
        
        cancelBtn.onclick = () => {
            this.hide();
            if (options.onCancel) options.onCancel();
        };
        
        modal.classList.remove('hidden');
    },
    
    hide() {
        document.getElementById('globalModal').classList.add('hidden');
    },
    
    success(message, title = '¡Éxito!', onConfirm = null) {
        this.show({ type: 'success', title, message, onConfirm });
    },
    
    error(message, title = 'Error', onConfirm = null) {
        this.show({ type: 'error', title, message, onConfirm });
    },
    
    warning(message, title = 'Advertencia', onConfirm = null) {
        this.show({ type: 'warning', title, message, onConfirm });
    },
    
    info(message, title = 'Información', onConfirm = null) {
        this.show({ type: 'info', title, message, onConfirm });
    },
    
    confirm(message, title = '¿Estás seguro?', onConfirm = null, onCancel = null) {
        this.show({ 
            type: 'warning', 
            title, 
            message, 
            showCancel: true,
            confirmText: 'Confirmar',
            cancelText: 'Cancelar',
            onConfirm, 
            onCancel 
        });
    }
};
</script>
