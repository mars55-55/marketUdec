<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            💬 {{ __('Mis Conversaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex h-96">
                    <!-- Lista de conversaciones -->
                    <div class="w-1/3 border-r border-gray-200 dark:border-gray-600 overflow-y-auto">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Conversaciones
                            </h3>
                        </div>
                        
                        @if($conversations->count() > 0)
                            @foreach($conversations as $conversation)
                                @php
                                    $otherUser = $conversation->user1_id == auth()->id() ? $conversation->user2 : $conversation->user1;
                                    $lastMessage = $conversation->messages()->latest()->first();
                                    $unreadCount = $conversation->messages()
                                        ->where('sender_id', '!=', auth()->id())
                                        ->whereNull('read_at')
                                        ->count();
                                @endphp
                                
                                <div class="conversation-item p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer {{ $selectedConversation && $selectedConversation->id == $conversation->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}"
                                     onclick="selectConversation({{ $conversation->id }})">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($otherUser->profile_photo)
                                                <img src="{{ asset('storage/' . $otherUser->profile_photo) }}" 
                                                     alt="{{ $otherUser->name }}"
                                                     class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 dark:text-gray-300 font-semibold">
                                                        {{ substr($otherUser->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                    {{ $otherUser->name }}
                                                </p>
                                                @if($unreadCount > 0)
                                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                                        {{ $unreadCount }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if($conversation->listing)
                                                <p class="text-xs text-blue-600 dark:text-blue-400 truncate">
                                                    Sobre: {{ $conversation->listing->title }}
                                                </p>
                                            @endif
                                            
                                            @php
                                                $isBlockedByMe = $conversation->isBlockedBy(auth()->id());
                                                $isBlockedByOther = $conversation->isBlockedBy($otherUser->id);
                                            @endphp
                                            
                                            @if($isBlockedByMe || $isBlockedByOther)
                                                <p class="text-xs text-red-600 dark:text-red-400 font-semibold">
                                                    @if($isBlockedByMe)
                                                        🔒 Has bloqueado esta conversación
                                                    @else
                                                        🚫 Te han bloqueado
                                                    @endif
                                                </p>
                                            @endif
                                            
                                            @if($lastMessage)
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                    {{ $lastMessage->sender_id == auth()->id() ? 'Tú: ' : '' }}
                                                    {{ Str::limit($lastMessage->message, 30) }}
                                                </p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                                    {{ $lastMessage->created_at->diffForHumans() }}
                                                </p>
                                            @else
                                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">
                                                    Nueva conversación
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-8 text-center">
                                <div class="text-4xl mb-4">💬</div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">
                                    No tienes conversaciones aún
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Área de mensajes -->
                    <div class="flex-1 flex flex-col">
                        @if($selectedConversation)
                            @php
                                $otherUser = $selectedConversation->user1_id == auth()->id() 
                                    ? $selectedConversation->user2 
                                    : $selectedConversation->user1;
                            @endphp
                            
                            <!-- Header de la conversación -->
                            <div class="p-4 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        @if($otherUser->profile_photo)
                                            <img src="{{ asset('storage/' . $otherUser->profile_photo) }}" 
                                                 alt="{{ $otherUser->name }}"
                                                 class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                <span class="text-gray-600 dark:text-gray-300 text-sm font-semibold">
                                                    {{ substr($otherUser->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $otherUser->name }}
                                            </h4>
                                            @if($selectedConversation->listing)
                                                <p class="text-xs text-blue-600 dark:text-blue-400">
                                                    Conversando sobre: 
                                                    <a href="{{ route('listings.show', $selectedConversation->listing) }}" class="underline">
                                                        {{ $selectedConversation->listing->title }}
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <!-- Opciones de conversación -->
                                        <div class="flex items-center space-x-2">
                                            @php
                                                $isBlocked = $selectedConversation->isBlockedBy(auth()->id());
                                                $isBlockedByOther = $selectedConversation->isBlockedBy($otherUser->id);
                                            @endphp
                                            
                                            @if($isBlockedByOther)
                                                <span class="text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded">
                                                    🚫 Te han bloqueado
                                                </span>
                                            @elseif($isBlocked)
                                                <button onclick="unblockUser({{ $selectedConversation->id }})"
                                                        class="text-xs text-green-600 hover:text-green-800 dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 px-2 py-1 rounded transition">
                                                    🔓 Desbloquear
                                                </button>
                                            @else
                                                <button onclick="blockUser({{ $selectedConversation->id }})"
                                                        class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 px-2 py-1 rounded transition">
                                                    🚫 Bloquear
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($selectedConversation->listing)
                                        <a href="{{ route('listings.show', $selectedConversation->listing) }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                            Ver anuncio →
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Mensajes -->
                            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messagesContainer">
                                @forelse($messages as $message)
                                    <div class="flex {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-xs lg:max-w-md">
                                            <div class="flex items-end space-x-2 {{ $message->sender_id == auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                                @if($message->sender_id != auth()->id())
                                                    @if($otherUser->profile_photo)
                                                        <img src="{{ asset('storage/' . $otherUser->profile_photo) }}" 
                                                             alt="{{ $otherUser->name }}"
                                                             class="w-6 h-6 rounded-full object-cover">
                                                    @else
                                                        <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                            <span class="text-gray-600 dark:text-gray-300 text-xs">
                                                                {{ substr($otherUser->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endif
                                                
                                                <div class="px-4 py-2 rounded-lg {{ $message->sender_id == auth()->id() 
                                                    ? 'bg-blue-600 text-white' 
                                                    : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">
                                                    <p class="text-sm">{{ $message->message }}</p>
                                                    <p class="text-xs opacity-75 mt-1">
                                                        {{ $message->created_at->format('H:i') }}
                                                        @if($message->sender_id == auth()->id() && $message->read_at)
                                                            <span class="ml-1">✓✓</span>
                                                        @elseif($message->sender_id == auth()->id())
                                                            <span class="ml-1">✓</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div class="text-4xl mb-2">👋</div>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            ¡Inicia la conversación!
                                        </p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Input para nuevo mensaje -->
                            <div class="p-4 border-t border-gray-200 dark:border-gray-600">
                                @php
                                    $canSendMessages = !$selectedConversation->isBlockedBy(auth()->id()) && 
                                                     !$selectedConversation->isBlockedBy($otherUser->id);
                                @endphp
                                
                                @if($canSendMessages)
                                    <form id="messageForm" class="flex space-x-2">
                                        @csrf
                                        <input type="hidden" name="conversation_id" value="{{ $selectedConversation->id }}">
                                        <input type="text" 
                                               name="message" 
                                               id="messageInput"
                                               placeholder="Escribe un mensaje..."
                                               class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                               maxlength="1000"
                                               required>
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                            Enviar
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center py-4">
                                        @if($selectedConversation->isBlockedBy($otherUser->id))
                                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                                <div class="flex items-center justify-center space-x-2 text-red-600 dark:text-red-400">
                                                    <span class="text-2xl">🚫</span>
                                                    <div>
                                                        <p class="font-semibold">No puedes enviar mensajes</p>
                                                        <p class="text-sm">{{ $otherUser->name }} te ha bloqueado</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($selectedConversation->isBlockedBy(auth()->id()))
                                            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                                <div class="flex items-center justify-center space-x-2 text-gray-600 dark:text-gray-400">
                                                    <span class="text-2xl">🔒</span>
                                                    <div>
                                                        <p class="font-semibold">Conversación bloqueada</p>
                                                        <p class="text-sm">Has bloqueado a {{ $otherUser->name }}</p>
                                                        <button onclick="unblockUser({{ $selectedConversation->id }})"
                                                                class="mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 underline">
                                                            Desbloquear para enviar mensajes
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="flex-1 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-6xl mb-4">💬</div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                        Selecciona una conversación
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        Elige una conversación de la lista para comenzar a chatear
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Seleccionar conversación
        function selectConversation(conversationId) {
            window.location.href = `{{ route('conversations.index') }}?conversation=${conversationId}`;
        }

        // Envío de mensajes
        document.addEventListener('DOMContentLoaded', function() {
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const messagesContainer = document.getElementById('messagesContainer');

            if (messageForm) {
                messageForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const messageText = messageInput.value.trim();
                    
                    if (!messageText) return;
                    
                    // Deshabilitar input mientras se envía
                    messageInput.disabled = true;
                    
                    fetch('{{ route("messages.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Limpiar input
                            messageInput.value = '';
                            
                            // Agregar mensaje al contenedor
                            addMessageToChat(data.message, true);
                            
                            // Scroll al final
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        } else {
                            alert('Error al enviar el mensaje');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al enviar el mensaje');
                    })
                    .finally(() => {
                        messageInput.disabled = false;
                        messageInput.focus();
                    });
                });

                // Enter para enviar
                messageInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        messageForm.dispatchEvent(new Event('submit'));
                    }
                });

                // Auto-scroll al cargar
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }
        });

        // Agregar mensaje al chat
        function addMessageToChat(message, isOwn) {
            const messagesContainer = document.getElementById('messagesContainer');
            if (!messagesContainer) return;

            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${isOwn ? 'justify-end' : 'justify-start'}`;
            
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            
            messageDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md">
                    <div class="px-4 py-2 rounded-lg ${isOwn 
                        ? 'bg-blue-600 text-white' 
                        : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100'}">
                        <p class="text-sm">${message}</p>
                        <p class="text-xs opacity-75 mt-1">
                            ${timeString}
                            ${isOwn ? '<span class="ml-1">✓</span>' : ''}
                        </p>
                    </div>
                </div>
            `;
            
            messagesContainer.appendChild(messageDiv);
        }

        // Polling para nuevos mensajes (cada 5 segundos)
        @if($selectedConversation)
            setInterval(function() {
                checkForNewMessages();
            }, 5000);

            function checkForNewMessages() {
                const lastMessageElement = document.querySelector('#messagesContainer .flex:last-child');
                const lastMessageTime = lastMessageElement ? 
                    lastMessageElement.dataset.time || '{{ $messages->last()?->created_at->toISOString() ?? "" }}' : 
                    '';

                fetch(`{{ route('messages.check', $selectedConversation) }}?after=${lastMessageTime}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(message => {
                                addMessageToChat(message.message, false);
                            });
                            
                            // Scroll al final
                            const messagesContainer = document.getElementById('messagesContainer');
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                    })
                    .catch(error => console.error('Error checking for new messages:', error));
            }
        @endif

        // Funciones de bloqueo y desbloqueo
        function blockUser(conversationId) {
            if (!confirm('¿Estás seguro de que quieres bloquear a esta persona? No podrán enviarte mensajes.')) {
                return;
            }

            fetch(`/conversations/${conversationId}/block`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Usuario bloqueado exitosamente', 'success');
                    // Recargar la página para actualizar la interfaz
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(data.message || 'Error al bloquear usuario', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al bloquear usuario', 'error');
            });
        }

        function unblockUser(conversationId) {
            if (!confirm('¿Estás seguro de que quieres desbloquear a esta persona? Podrán enviarte mensajes nuevamente.')) {
                return;
            }

            fetch(`/conversations/${conversationId}/unblock`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Usuario desbloqueado exitosamente', 'success');
                    // Recargar la página para actualizar la interfaz
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(data.message || 'Error al desbloquear usuario', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al desbloquear usuario', 'error');
            });
        }

        // Función para mostrar notificaciones
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