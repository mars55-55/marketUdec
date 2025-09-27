<!-- Modal para reportar -->
<div id="reportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    🚨 Reportar Contenido
                </h3>
                <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Información del elemento a reportar -->
            <div id="reportItemInfo" class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-4">
                <!-- Se llenará dinámicamente -->
            </div>

            <!-- Formulario -->
            <form id="reportForm" method="POST" action="{{ route('reports.store') }}">
                @csrf
                <input type="hidden" id="reportType" name="type">
                <input type="hidden" id="reportListingId" name="listing_id">
                <input type="hidden" id="reportUserId" name="user_id">

                <!-- Razón del reporte -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        ¿Por qué estás reportando este contenido?
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="reason" value="inappropriate" 
                                   class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-900" required>
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                🔞 Contenido inapropiado u ofensivo
                            </span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="radio" name="reason" value="spam" 
                                   class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-900">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                📧 Spam o contenido repetitivo
                            </span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="radio" name="reason" value="fraud" 
                                   class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-900">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                ⚠️ Posible fraude o estafa
                            </span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="radio" name="reason" value="fake" 
                                   class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-900">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                🎭 Información falsa o engañosa
                            </span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="radio" name="reason" value="other" 
                                   class="text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-900">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                ❓ Otro motivo
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                    <label for="reportDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Describe el problema (obligatorio)
                    </label>
                    <textarea id="reportDescription" 
                              name="description" 
                              rows="4" 
                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                              placeholder="Proporciona más detalles sobre por qué estás reportando este contenido..."
                              maxlength="1000"
                              required></textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span id="descriptionCount">0</span>/1000 caracteres
                    </p>
                </div>

                <!-- Información adicional -->
                <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>ℹ️ Información importante:</strong>
                    </p>
                    <ul class="text-xs text-blue-700 dark:text-blue-300 mt-1 space-y-1">
                        <li>• Los reportes son revisados por nuestro equipo de moderación</li>
                        <li>• Los reportes falsos pueden resultar en restricciones a tu cuenta</li>
                        <li>• Tu identidad se mantendrá confidencial</li>
                    </ul>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" 
                            onclick="closeReportModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                        Enviar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openReportModal(type, id, title, itemInfo = {}) {
        // Llenar información del elemento
        const reportItemInfo = document.getElementById('reportItemInfo');
        
        if (type === 'listing') {
            reportItemInfo.innerHTML = `
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">${itemInfo.icon || '📦'}</span>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Anuncio: ${title}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Por: ${itemInfo.userName || 'Usuario'}</p>
                    </div>
                </div>
            `;
            document.getElementById('reportListingId').value = id;
            document.getElementById('reportUserId').value = '';
        } else if (type === 'user') {
            reportItemInfo.innerHTML = `
                <div class="flex items-center space-x-3">
                    ${itemInfo.photo ? 
                        `<img src="${itemInfo.photo}" alt="${title}" class="w-10 h-10 rounded-full object-cover">` :
                        `<div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <span class="text-gray-600 dark:text-gray-300 font-semibold">${title.charAt(0)}</span>
                        </div>`
                    }
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Usuario: ${title}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${itemInfo.career || 'Usuario de la plataforma'}</p>
                    </div>
                </div>
            `;
            document.getElementById('reportUserId').value = id;
            document.getElementById('reportListingId').value = '';
        }

        // Establecer tipo
        document.getElementById('reportType').value = type;

        // Resetear formulario
        resetReportForm();

        // Mostrar modal
        document.getElementById('reportModal').classList.remove('hidden');
    }

    function closeReportModal() {
        document.getElementById('reportModal').classList.add('hidden');
        resetReportForm();
    }

    function resetReportForm() {
        // Limpiar selecciones de radio
        const radioButtons = document.querySelectorAll('input[name="reason"]');
        radioButtons.forEach(radio => radio.checked = false);
        
        // Limpiar descripción
        document.getElementById('reportDescription').value = '';
        updateDescriptionCount();
    }

    function updateDescriptionCount() {
        const description = document.getElementById('reportDescription').value;
        document.getElementById('descriptionCount').textContent = description.length;
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Contador de caracteres
        document.getElementById('reportDescription').addEventListener('input', updateDescriptionCount);

        // Cerrar modal al hacer clic fuera
        document.getElementById('reportModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReportModal();
            }
        });
    });
</script>