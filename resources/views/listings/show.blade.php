<x-app-layout>
    <!-- Notificaciones Flash -->
    @if(session('success'))
        <div id="successNotification" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div id="errorNotification" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            {{ session('error') ?? $errors->first() }}
        </div>
    @endif

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
                                        @if($listing->user->hasReviews())
                                            <span class="text-yellow-400">⭐</span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400 ml-1">
                                                {{ $listing->user->formatted_rating }} 
                                                ({{ $listing->user->rating_count }} {{ $listing->user->rating_count == 1 ? 'reseña' : 'reseñas' }})
                                            </span>
                                        @else
                                            <span class="text-gray-400">☆</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-500 ml-1">
                                                Sin reseñas aún
                                            </span>
                                        @endif
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

                    <!-- Dejar reseña -->
                    @auth
                        @if($listing->user_id !== auth()->id())
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-4">¿Ya hiciste una transacción?</h3>
                                    <button onclick="openReviewModal({{ $listing->user_id }}, {{ $listing->id }})"
                                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                        ⭐ Dejar Reseña
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
            const listingTitle = '{{ $listing->title }}';
            const userName = '{{ $listing->user->name }}';
            
            openReportModal('listing', listingId, listingTitle, {
                icon: '{{ $listing->category->icon ?? "📦" }}',
                userName: userName
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
        // Reportar anuncio
        function reportListing(listingId) {
            const listingTitle = '{{ $listing->title }}';
            const userName = '{{ $listing->user->name }}';
            
            openReportModal('listing', listingId, listingTitle, {
                icon: '{{ $listing->category->icon ?? "📦" }}',
                userName: userName
            });
        }

        // Abrir modal de reseña
        function openReviewModal(userId, listingId) {
            document.getElementById('reviewUserId').value = userId;
            document.getElementById('reviewListingId').value = listingId;
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            // Resetear formulario
            document.getElementById('reviewForm').reset();
            updateStarRating(0);
        }

        // Sistema de estrellas
        function updateStarRating(rating) {
            const stars = document.querySelectorAll('.star-rating');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.textContent = '⭐';
                    star.classList.add('active');
                } else {
                    star.textContent = '☆';
                    star.classList.remove('active');
                }
            });
            document.getElementById('ratingValue').value = rating;
            
            // Actualizar texto descriptivo
            const ratingText = document.getElementById('ratingText');
            const descriptions = {
                1: '1 estrella - Muy malo',
                2: '2 estrellas - Malo', 
                3: '3 estrellas - Regular',
                4: '4 estrellas - Bueno',
                5: '5 estrellas - Excelente'
            };
            ratingText.textContent = descriptions[rating];
            ratingText.className = 'text-xs text-yellow-600 dark:text-yellow-400 font-medium';
        }
        @endauth
    </script>

    <!-- Modal de reportes -->
    @auth
        @include('components.report-modal')
    @endauth

    <!-- Modal de reseñas -->
    @auth
        <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            ⭐ Dejar Reseña
                        </h3>
                        <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Información del usuario -->
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-4">
                        <div class="flex items-center space-x-3">
                            @if($listing->user->profile_photo_path)
                                <img src="{{ Storage::url($listing->user->profile_photo_path) }}" alt="{{ $listing->user->name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <span class="text-gray-600 dark:text-gray-300 font-semibold">{{ substr($listing->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $listing->user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $listing->user->career ?? 'Miembro de la comunidad' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form id="reviewForm" method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                        <input type="hidden" id="reviewUserId" name="user_id">
                        <input type="hidden" id="reviewListingId" name="listing_id">
                        <input type="hidden" id="ratingValue" name="rating" value="0">

                        <!-- Calificación -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Calificación
                            </label>
                            <div class="flex space-x-1 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="star-rating text-2xl cursor-pointer hover:scale-110 transition" 
                                            onclick="updateStarRating({{ $i }})" 
                                            title="{{ $i }} {{ $i == 1 ? 'estrella' : 'estrellas' }}">☆</button>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <span id="ratingText">Selecciona una calificación (requerido)</span>
                            </p>
                        </div>

                        <!-- Comentario -->
                        <div class="mb-4">
                            <label for="reviewComment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Comentario (opcional)
                            </label>
                            <textarea id="reviewComment" 
                                      name="comment" 
                                      rows="4" 
                                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm"
                                      placeholder="Cuenta tu experiencia con esta persona..."
                                      maxlength="1000"></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <span id="commentCount">0</span>/1000 caracteres
                            </p>
                        </div>

                        <!-- Información adicional -->
                        <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                <strong>💡 Tip:</strong> Las reseñas ayudan a otros usuarios a tomar decisiones informadas.
                            </p>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-3">
                            <button type="button" 
                                    onclick="closeReviewModal()"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    id="submitReviewBtn"
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition">
                                Enviar Reseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Auto-ocultar notificaciones
            document.addEventListener('DOMContentLoaded', function() {
                // Ocultar notificaciones después de 5 segundos
                const successNotification = document.getElementById('successNotification');
                const errorNotification = document.getElementById('errorNotification');
                
                if (successNotification) {
                    setTimeout(() => {
                        successNotification.style.opacity = '0';
                        setTimeout(() => successNotification.remove(), 300);
                    }, 5000);
                }
                
                if (errorNotification) {
                    setTimeout(() => {
                        errorNotification.style.opacity = '0';
                        setTimeout(() => errorNotification.remove(), 300);
                    }, 7000);
                }

                // Contador de caracteres para comentario
                const commentInput = document.getElementById('reviewComment');
                if (commentInput) {
                    commentInput.addEventListener('input', function() {
                        document.getElementById('commentCount').textContent = this.value.length;
                    });
                }

                // Cerrar modal al hacer clic fuera
                document.getElementById('reviewModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeReviewModal();
                    }
                });

                // Manejar envío de reseña
                document.getElementById('reviewForm').addEventListener('submit', function(e) {
                    const rating = document.getElementById('ratingValue').value;
                    if (rating === '0' || rating === '') {
                        e.preventDefault();
                        showNotification('Por favor, selecciona una calificación de 1 a 5 estrellas', 'error');
                        return;
                    }

                    // Mostrar loading
                    const submitBtn = document.getElementById('submitReviewBtn');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enviando...
                    `;
                    submitBtn.disabled = true;
                });
            });
        </script>
    @endauth
</x-app-layout>