<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ❤️ {{ __('Mis Favoritos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($favorites->count() > 0)
                <div class="mb-6">
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ $favorites->total() }} {{ $favorites->total() == 1 ? 'anuncio guardado' : 'anuncios guardados' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($favorites as $favorite)
                        @php($listing = $favorite->listing)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                            <div class="relative">
                                <!-- Imagen -->
                                <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-700">
                                    @if($listing->primaryImage)
                                        <img src="{{ asset('storage/' . $listing->primaryImage->image_path) }}" 
                                             alt="{{ $listing->title }}"
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                            <span class="text-4xl">{{ $listing->category->icon ?? '📦' }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Botón para remover de favoritos -->
                                <button class="absolute top-2 right-2 favorite-btn bg-white dark:bg-gray-800 rounded-full p-2 shadow-md hover:shadow-lg transition"
                                        data-listing-id="{{ $listing->id }}"
                                        data-is-favorite="true"
                                        title="Remover de favoritos">
                                    <span class="favorite-icon text-lg">❤️</span>
                                </button>

                                <!-- Estado del anuncio -->
                                @if($listing->status !== 'active')
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                                            @if($listing->status === 'sold')
                                                Vendido
                                            @elseif($listing->status === 'paused')
                                                Pausado
                                            @elseif($listing->status === 'blocked')
                                                Bloqueado
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                        {{ $listing->title }}
                                    </h3>
                                    <span class="text-sm text-blue-600 dark:text-blue-400 ml-2">
                                        {{ $listing->category->icon }}
                                    </span>
                                </div>

                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                    {{ Str::limit($listing->description, 100) }}
                                </p>

                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        ${{ number_format($listing->price, 0) }}
                                        @if($listing->is_negotiable)
                                            <span class="text-xs text-gray-500">negociable</span>
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                        {{ ucfirst($listing->condition) }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                                    <span>Por {{ $listing->user->name }}</span>
                                    <span>{{ $listing->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                                    <div class="flex items-center space-x-2">
                                        <span>👁️ {{ $listing->views }}</span>
                                        @if($listing->allows_exchange)
                                            <span>🔄 Intercambio</span>
                                        @endif
                                    </div>
                                    @if($listing->location)
                                        <span>📍 {{ $listing->location }}</span>
                                    @endif
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ route('listings.show', $listing) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded transition">
                                        Ver Detalles
                                    </a>
                                    
                                    @if($listing->status === 'active' && $listing->user_id !== auth()->id())
                                        <button onclick="startConversation({{ $listing->id }})"
                                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-3 rounded transition"
                                                title="Contactar vendedor">
                                            💬
                                        </button>
                                    @endif
                                </div>

                                <!-- Fecha agregado a favoritos -->
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        ❤️ Guardado {{ $favorite->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-8">
                    {{ $favorites->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">💔</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            No tienes favoritos aún
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Cuando encuentres anuncios interesantes, guárdalos aquí para verlos más tarde.
                            <br>
                            Solo haz clic en el ícono 🤍 de cualquier anuncio.
                        </p>
                        <a href="{{ route('home') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            🔍 Explorar Anuncios
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Funcionalidad de favoritos
        document.addEventListener('DOMContentLoaded', function() {
            const favoriteButtons = document.querySelectorAll('.favorite-btn');
            
            favoriteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const listingId = this.dataset.listingId;
                    const card = this.closest('.bg-white');
                    
                    fetch('{{ route("favorites.toggle") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            listing_id: listingId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Animar la eliminación de la tarjeta
                            card.style.transition = 'all 0.3s ease';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.95)';
                            
                            setTimeout(() => {
                                card.remove();
                                
                                // Verificar si no quedan más favoritos
                                const remainingCards = document.querySelectorAll('.bg-white.dark\\:bg-gray-800');
                                if (remainingCards.length === 0) {
                                    location.reload(); // Recargar para mostrar mensaje vacío
                                }
                            }, 300);
                            
                            // Mostrar notificación
                            showNotification(data.message, 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al procesar la solicitud', 'error');
                    });
                });
            });
        });

        // Iniciar conversación
        function startConversation(listingId) {
            // Redirigir a conversaciones con el anuncio específico
            window.location.href = `/conversations?listing_id=${listingId}`;
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
            }, 300);
        }
    </script>
</x-app-layout>