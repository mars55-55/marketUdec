<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🔔 {{ __('Notificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Todas las Notificaciones
                        </h3>
                        <button onclick="markAllAsRead()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            Marcar todas como leídas
                        </button>
                    </div>

                    <div id="notificationsContainer">
                        <div class="text-center py-8">
                            <div class="text-4xl mb-4">🔔</div>
                            <p class="text-gray-500">Cargando notificaciones...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadAllNotifications();
        });

        function loadAllNotifications() {
            fetch('{{ route("notifications.index") }}')
                .then(response => response.json())
                .then(data => {
                    renderAllNotifications(data.notifications);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    document.getElementById('notificationsContainer').innerHTML = `
                        <div class="text-center py-8">
                            <div class="text-4xl mb-4">❌</div>
                            <p class="text-red-500">Error al cargar las notificaciones</p>
                        </div>
                    `;
                });
        }

        function renderAllNotifications(notifications) {
            const container = document.getElementById('notificationsContainer');
            
            if (notifications.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="text-4xl mb-4">📭</div>
                        <p class="text-gray-500">No tienes notificaciones</p>
                    </div>
                `;
                return;
            }

            let html = '';
            notifications.forEach(notification => {
                const data = notification.data;
                const isRead = notification.read_at !== null;
                const createdAt = new Date(notification.created_at).toLocaleString('es-ES');
                
                // Determinar el estilo y contenido basado en el tipo de notificación
                let borderColor = 'border-gray-200';
                let bgColor = isRead ? 'opacity-75' : 'bg-blue-50 dark:bg-blue-900/10 border-blue-200';
                let avatar = '';
                let actionUrl = data.url || data.action_url || '#';
                let actionText = data.action_text || 'Ver';
                
                if (data.type === 'listing_blocked') {
                    borderColor = 'border-red-200';
                    bgColor = isRead ? 'opacity-75' : 'bg-red-50 dark:bg-red-900/10 border-red-200';
                    avatar = `<div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center text-white text-xl font-bold">🚫</div>`;
                } else if (data.type === 'new_message') {
                    borderColor = 'border-green-200';
                    bgColor = isRead ? 'opacity-75' : 'bg-green-50 dark:bg-green-900/10 border-green-200';
                    avatar = data.sender_avatar ? 
                        `<img src="${data.sender_avatar}" alt="${data.sender_name}" class="w-12 h-12 rounded-full object-cover">` :
                        `<div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white text-xl font-bold">💬</div>`;
                } else {
                    // Notificación genérica
                    avatar = data.sender_avatar ? 
                        `<img src="${data.sender_avatar}" alt="${data.sender_name || 'Usuario'}" class="w-12 h-12 rounded-full object-cover">` :
                        `<div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">🔔</div>`;
                }
                
                html += `
                    <div class="border ${borderColor} dark:border-gray-700 rounded-lg p-4 mb-4 hover:shadow-md transition ${bgColor}">
                        <div class="flex items-start space-x-4">
                            ${avatar}
                            
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                            ${data.title}
                                        </h4>
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                                            ${data.message}
                                        </p>
                                        ${data.reason ? `<p class="text-sm text-red-600 dark:text-red-400 mt-1 font-medium">Motivo: ${data.reason}</p>` : ''}
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            ${createdAt}
                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        ${!isRead ? '<div class="w-3 h-3 bg-blue-500 rounded-full"></div>' : ''}
                                        ${actionUrl !== '#' ? `
                                            <button onclick="openNotification('${notification.id}', '${actionUrl}')"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition">
                                                ${actionText}
                                            </button>
                                        ` : ''}
                                        <button onclick="deleteNotification('${notification.id}')"
                                                class="text-gray-400 hover:text-red-500 p-1 rounded transition">
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        function openNotification(notificationId, url) {
            fetch(`{{ url('/notifications') }}/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                window.location.href = url;
            });
        }

        function markAllAsRead() {
            fetch('{{ route("notifications.readAll") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                loadAllNotifications();
                showNotification('Todas las notificaciones marcadas como leídas', 'success');
            });
        }

        function deleteNotification(notificationId) {
            if (confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
                fetch(`{{ url('/notifications') }}/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    loadAllNotifications();
                    showNotification('Notificación eliminada', 'success');
                }).catch(() => {
                    showNotification('Error al eliminar la notificación', 'error');
                });
            }
        }

        function showNotification(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
</x-app-layout>