<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            👤 {{ __('Perfil de Usuario') }} - {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Información del Usuario -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center space-x-6">
                        <!-- Foto de perfil -->
                        <div class="flex-shrink-0">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     alt="{{ $user->name }}"
                                     class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600">
                            @else
                                <div class="w-24 h-24 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center border-4 border-gray-200 dark:border-gray-600">
                                    <span class="text-gray-600 dark:text-gray-300 text-2xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Información básica -->
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}
                                    </h1>
                                    
                                    @if($user->career)
                                        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                            🎓 {{ $user->career }}
                                        </p>
                                    @endif
                                    
                                    @if($user->campus)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            🏛️ Campus {{ $user->campus }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Calificación -->
                                @if($user->rating > 0)
                                    <div class="text-right">
                                        <div class="flex items-center justify-end space-x-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-lg {{ $i <= $user->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                                    ⭐
                                                </span>
                                            @endfor
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ number_format($user->rating, 1) }}/5 
                                            ({{ $user->reviews()->count() }} {{ $user->reviews()->count() == 1 ? 'reseña' : 'reseñas' }})
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Bio -->
                            @if($user->bio)
                                <div class="mt-4">
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $user->bio }}
                                    </p>
                                </div>
                            @endif

                            <!-- Estadísticas -->
                            <div class="mt-4 flex items-center space-x-6 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center space-x-1">
                                    <span>📦</span>
                                    <span>{{ $user->listings()->count() }} anuncios</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <span>✅</span>
                                    <span>{{ $user->listings()->where('status', 'sold')->count() }} vendidos</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <span>📅</span>
                                    <span>Miembro desde {{ $user->created_at->format('M Y') }}</span>
                                </div>
                            </div>

                            <!-- Acciones -->
                            @auth
                                @if(auth()->id() !== $user->id)
                                    <div class="mt-4 flex space-x-3">
                                        <button onclick="startConversation({{ $user->id }})"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                            💬 Enviar Mensaje
                                        </button>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Anuncios del Usuario -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            📦 Anuncios de {{ $user->name }}
                        </h2>
                        
                        <!-- Filtros -->
                        <div class="flex space-x-2">
                            <select id="statusFilter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-md text-sm">
                                <option value="">Todos los estados</option>
                                <option value="active">Activos</option>
                                <option value="sold">Vendidos</option>
                                <option value="paused">Pausados</option>
                            </select>
                            
                            <select id="sortOrder" class="border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-md text-sm">
                                <option value="newest">Más recientes</option>
                                <option value="oldest">Más antiguos</option>
                                <option value="price_asc">Precio: menor a mayor</option>
                                <option value="price_desc">Precio: mayor a menor</option>
                            </select>
                        </div>
                    </div>

                    @if($listings->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="listingsGrid">
                            @foreach($listings as $listing)
                                <div class="listing-card bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition"
                                     data-status="{{ $listing->status }}"
                                     data-price="{{ $listing->price }}"
                                     data-created="{{ $listing->created_at->timestamp }}">
                                    
                                    <div class="relative">
                                        <!-- Imagen -->
                                        <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-600">
                                            @if($listing->primaryImage)
                                                <img src="{{ asset('storage/' . $listing->primaryImage->image_path) }}" 
                                                     alt="{{ $listing->title }}"
                                                     class="w-full h-40 object-cover">
                                            @else
                                                <div class="w-full h-40 flex items-center justify-center bg-gray-100 dark:bg-gray-600">
                                                    <span class="text-3xl">{{ $listing->category->icon ?? '📦' }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Estado -->
                                        <div class="absolute top-2 left-2">
                                            @if($listing->status === 'active')
                                                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">Activo</span>
                                            @elseif($listing->status === 'sold')
                                                <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded">Vendido</span>
                                            @elseif($listing->status === 'paused')
                                                <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded">Pausado</span>
                                            @elseif($listing->status === 'blocked')
                                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">Bloqueado</span>
                                            @endif
                                        </div>

                                        <!-- Favorito (solo si está autenticado y no es el propietario) -->
                                        @auth
                                            @if(auth()->id() !== $listing->user_id)
                                                <button class="absolute top-2 right-2 favorite-btn bg-white dark:bg-gray-800 rounded-full p-2 shadow-md hover:shadow-lg transition"
                                                        data-listing-id="{{ $listing->id }}"
                                                        data-is-favorite="{{ auth()->user()->favorites()->where('listing_id', $listing->id)->exists() }}"
                                                        title="Agregar a favoritos">
                                                    <span class="favorite-icon text-lg">
                                                        {{ auth()->user()->favorites()->where('listing_id', $listing->id)->exists() ? '❤️' : '🤍' }}
                                                    </span>
                                                </button>
                                            @endif
                                        @endauth
                                    </div>

                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 truncate">
                                            {{ $listing->title }}
                                        </h3>

                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                            {{ Str::limit($listing->description, 100) }}
                                        </p>

                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xl font-bold text-green-600 dark:text-green-400">
                                                ${{ number_format($listing->price, 0) }}
                                                @if($listing->is_negotiable)
                                                    <span class="text-xs text-gray-500">negociable</span>
                                                @endif
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                                                {{ ucfirst($listing->condition) }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                                            <span>{{ $listing->created_at->diffForHumans() }}</span>
                                            <span>👁️ {{ $listing->views }} vistas</span>
                                        </div>

                                        <a href="{{ route('listings.show', $listing) }}" 
                                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded transition">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-8">
                            {{ $listings->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📦</div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                {{ $user->name }} no tiene anuncios aún
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                @auth
                                    @if(auth()->id() === $user->id)
                                        ¿Listo para vender algo?
                                    @else
                                        Mantente al tanto, podrían publicar algo pronto.
                                    @endif
                                @else
                                    Este usuario no ha publicado anuncios todavía.
                                @endauth
                            </p>
                            
                            @auth
                                @if(auth()->id() === $user->id)
                                    <a href="{{ route('listings.create') }}" 
                                       class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        🚀 Publicar mi primer anuncio
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reseñas -->
            @if($reviews->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            ⭐ Reseñas de {{ $user->name }}
                        </h2>

                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="border-b border-gray-200 dark:border-gray-600 pb-6 last:border-b-0">
                                    <div class="flex items-start space-x-4">
                                        <!-- Avatar del reviewer -->
                                        <div class="flex-shrink-0">
                                            @if($review->reviewer->profile_photo)
                                                <img src="{{ asset('storage/' . $review->reviewer->profile_photo) }}" 
                                                     alt="{{ $review->reviewer->name }}"
                                                     class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 dark:text-gray-300 font-semibold">
                                                        {{ substr($review->reviewer->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $review->reviewer->name }}
                                                    </h4>
                                                    <div class="flex items-center space-x-1 mt-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <span class="text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                                                ⭐
                                                            </span>
                                                        @endfor
                                                        <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">
                                                            {{ $review->rating }}/5
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $review->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            @if($review->comment)
                                                <p class="mt-3 text-gray-700 dark:text-gray-300">
                                                    {{ $review->comment }}
                                                </p>
                                            @endif

                                            @if($review->listing)
                                                <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        Reseña sobre: 
                                                        <a href="{{ route('listings.show', $review->listing) }}" 
                                                           class="text-blue-600 dark:text-blue-400 hover:underline">
                                                            {{ $review->listing->title }}
                                                        </a>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($reviews->count() >= 5)
                            <div class="mt-6 text-center">
                                <a href="{{ route('reviews.show', $user) }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:underline">
                                    Ver todas las reseñas →
                                </a>
                            </div>
                        @endif
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
                    const isFavorite = this.dataset.isFavorite === 'true';
                    
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
                            const icon = this.querySelector('.favorite-icon');
                            if (data.is_favorite) {
                                icon.textContent = '❤️';
                                this.dataset.isFavorite = 'true';
                            } else {
                                icon.textContent = '🤍';
                                this.dataset.isFavorite = 'false';
                            }
                            showNotification(data.message, 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al procesar la solicitud', 'error');
                    });
                });
            });

            // Filtros
            const statusFilter = document.getElementById('statusFilter');
            const sortOrder = document.getElementById('sortOrder');
            const listingsGrid = document.getElementById('listingsGrid');

            function filterAndSort() {
                const status = statusFilter.value;
                const sort = sortOrder.value;
                const cards = Array.from(document.querySelectorAll('.listing-card'));

                // Filtrar
                cards.forEach(card => {
                    const cardStatus = card.dataset.status;
                    if (!status || cardStatus === status) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Ordenar
                const visibleCards = cards.filter(card => card.style.display !== 'none');
                visibleCards.sort((a, b) => {
                    switch(sort) {
                        case 'oldest':
                            return parseInt(a.dataset.created) - parseInt(b.dataset.created);
                        case 'price_asc':
                            return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                        case 'price_desc':
                            return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                        default: // newest
                            return parseInt(b.dataset.created) - parseInt(a.dataset.created);
                    }
                });

                // Reordenar en el DOM
                visibleCards.forEach(card => {
                    listingsGrid.appendChild(card);
                });
            }

            statusFilter.addEventListener('change', filterAndSort);
            sortOrder.addEventListener('change', filterAndSort);
        });

        // Iniciar conversación
        function startConversation(userId) {
            window.location.href = `/conversations?user_id=${userId}`;
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