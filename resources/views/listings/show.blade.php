<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $listing->title }}
            </h2>
            @auth
                @if($listing->user_id === auth()->id())
                    <div class="flex space-x-2">
                        <a href="{{ route('listings.edit', $listing) }}" 
                           class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            ✏️ Editar
                        </a>
                        @if($listing->status === 'active')
                            <form method="POST" action="{{ route('listings.toggle-status', $listing) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                    ⏸️ Pausar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('listings.mark-sold', $listing) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('¿Marcar como vendido?')">
                                    ✅ Vendido
                                </button>
                            </form>
                        @elseif($listing->status === 'paused')
                            <form method="POST" action="{{ route('listings.toggle-status', $listing) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    ▶️ Activar
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Columna principal - Imágenes y descripción -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Estado del anuncio -->
                    @if($listing->status !== 'active')
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="text-yellow-400 text-xl mr-3">
                                    @if($listing->status === 'sold')
                                        ✅
                                    @elseif($listing->status === 'paused')
                                        ⏸️
                                    @elseif($listing->status === 'blocked')
                                        🚫
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-yellow-800 dark:text-yellow-200 font-medium">
                                        @if($listing->status === 'sold')
                                            Anuncio vendido
                                        @elseif($listing->status === 'paused')
                                            Anuncio pausado
                                        @elseif($listing->status === 'blocked')
                                            Anuncio bloqueado
                                        @endif
                                    </h3>
                                    <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                                        @if($listing->status === 'sold')
                                            Este producto ya no está disponible.
                                        @elseif($listing->status === 'paused')
                                            El vendedor ha pausado temporalmente este anuncio.
                                        @elseif($listing->status === 'blocked')
                                            Este anuncio ha sido bloqueado por moderación.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Galería de imágenes -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        @if($listing->images->count() > 0)
                            <div class="relative">
                                <!-- Imagen principal -->
                                <div id="main-image" class="aspect-w-16 aspect-h-12">
                                    <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}" 
                                         alt="{{ $listing->title }}"
                                         class="w-full h-96 object-cover">
                                </div>
                                
                                <!-- Thumbnails -->
                                @if($listing->images->count() > 1)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700">
                                        <div class="flex space-x-2 overflow-x-auto">
                                            @foreach($listing->images as $index => $image)
                                                <button onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')"
                                                        class="flex-shrink-0 thumbnail {{ $index === 0 ? 'ring-2 ring-blue-500' : '' }}">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                         alt="{{ $listing->title }} - {{ $index + 1 }}"
                                                         class="w-16 h-16 object-cover rounded">
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="h-96 flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                <div class="text-center">
                                    <span class="text-8xl">{{ $listing->category->icon ?? '📦' }}</span>
                                    <p class="text-gray-500 dark:text-gray-400 mt-4">Sin imágenes</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Descripción -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Descripción</h3>
                            <div class="prose dark:prose-invert max-w-none">
                                {!! nl2br(e($listing->description)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Detalles adicionales -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Detalles</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Estado:</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($listing->condition) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Categoría:</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $listing->category->icon }} {{ $listing->category->name }}
                                    </p>
                                </div>
                                @if($listing->location)
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Ubicación:</span>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">📍 {{ $listing->location }}</p>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Publicado:</span>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $listing->created_at->format('d/m/Y') }}</p>
                                </div>
                                @if($listing->is_negotiable)
                                    <div class="col-span-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            💰 Precio negociable
                                        </span>
                                    </div>
                                @endif
                                @if($listing->allows_exchange)
                                    <div class="col-span-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            🔄 Acepta intercambios
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna lateral - Precio y vendedor -->
                <div class="space-y-6">
                    <!-- Precio y acciones -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <div class="text-4xl font-bold text-green-600 dark:text-green-400 mb-2">
                                    ${{ number_format($listing->price, 0) }}
                                </div>
                                @if($listing->is_negotiable)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Precio negociable</p>
                                @endif
                            </div>

                            @auth
                                @if($listing->user_id !== auth()->id() && $listing->status === 'active')
                                    <div class="space-y-3">
                                        <!-- Contactar vendedor -->
                                        <button onclick="startConversation({{ $listing->id }})"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                                            💬 Contactar Vendedor
                                        </button>

                                        <!-- Agregar a favoritos -->
                                        <button class="w-full favorite-btn border-2 border-gray-300 hover:border-red-300 text-gray-700 dark:text-gray-300 font-bold py-3 px-4 rounded-lg flex items-center justify-center transition"
                                                data-listing-id="{{ $listing->id }}"
                                                data-is-favorite="{{ $listing->isFavoritedBy(auth()->user()) ? 'true' : 'false' }}">
                                            <span class="favorite-icon text-xl mr-2">
                                                {{ $listing->isFavoritedBy(auth()->user()) ? '❤️' : '🤍' }}
                                            </span>
                                            <span class="favorite-text">
                                                {{ $listing->isFavoritedBy(auth()->user()) ? 'En Favoritos' : 'Agregar a Favoritos' }}
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="space-y-3">
                                    <a href="{{ route('login') }}" 
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                                        🔑 Iniciar Sesión para Contactar
                                    </a>
                                </div>
                            @endauth

                            <!-- Estadísticas -->
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <span>👁️ {{ $listing->views }} vistas</span>
                                    <span>📅 {{ $listing->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del vendedor -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Vendedor</h3>
                            <div class="flex items-center space-x-4">
                                <img src="{{ $listing->user->avatar }}" 
                                     alt="{{ $listing->user->name }}"
                                     class="w-12 h-12 rounded-full">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $listing->user->name }}</h4>
                                    @if($listing->user->career || $listing->user->campus)
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            @if($listing->user->career)
                                                {{ $listing->user->career }}
                                            @endif
                                            @if($listing->user->career && $listing->user->campus) • @endif
                                            @if($listing->user->campus)
                                                {{ $listing->user->campus }}
                                            @endif
                                        </p>
                                    @endif
                                    <div class="flex items-center mt-1">
                                        <span class="text-yellow-400">⭐</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400 ml-1">
                                            {{ number_format($listing->user->rating, 1) }} 
                                            ({{ $listing->user->rating_count }} {{ $listing->user->rating_count == 1 ? 'reseña' : 'reseñas' }})
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($listing->user->bio)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $listing->user->bio }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reportar anuncio -->
                    @auth
                        @if($listing->user_id !== auth()->id())
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <button onclick="reportListing({{ $listing->id }})"
                                            class="w-full text-red-600 hover:text-red-700 dark:text-red-400 text-sm flex items-center justify-center">
                                        🚩 Reportar anuncio
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cambiar imagen principal
        function changeMainImage(imageSrc) {
            document.querySelector('#main-image img').src = imageSrc;
            
            // Actualizar estado de thumbnails
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('ring-2', 'ring-blue-500');
            });
            event.target.closest('.thumbnail').classList.add('ring-2', 'ring-blue-500');
        }

        @auth
        // Funcionalidad de favoritos
        document.addEventListener('DOMContentLoaded', function() {
            const favoriteButton = document.querySelector('.favorite-btn');
            
            if (favoriteButton) {
                favoriteButton.addEventListener('click', function() {
                    const listingId = this.dataset.listingId;
                    const icon = this.querySelector('.favorite-icon');
                    const text = this.querySelector('.favorite-text');
                    
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
                            icon.textContent = data.is_favorite ? '❤️' : '🤍';
                            text.textContent = data.is_favorite ? 'En Favoritos' : 'Agregar a Favoritos';
                            this.dataset.isFavorite = data.is_favorite;
                            
                            // Cambiar estilo del botón
                            if (data.is_favorite) {
                                this.classList.remove('border-gray-300', 'hover:border-red-300');
                                this.classList.add('border-red-300', 'bg-red-50', 'dark:bg-red-900/20');
                            } else {
                                this.classList.add('border-gray-300', 'hover:border-red-300');
                                this.classList.remove('border-red-300', 'bg-red-50', 'dark:bg-red-900/20');
                            }
                            
                            // Mostrar notificación
                            showNotification(data.message, 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al procesar la solicitud', 'error');
                    });
                });
            }
        });

        // Iniciar conversación
        function startConversation(listingId) {
            // Por ahora redirigir a crear conversación
            window.location.href = `/conversations?listing_id=${listingId}`;
        }

        // Reportar anuncio
        function reportListing(listingId) {
            if (confirm('¿Estás seguro de que quieres reportar este anuncio?')) {
                // Implementar funcionalidad de reportes
                showNotification('Funcionalidad de reportes próximamente', 'info');
            }
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
        @endauth
    </script>
</x-app-layout>