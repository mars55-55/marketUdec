<x-app-layout>
    <!-- Notificaciones Flash -->
    @if(session('success'))
        <div id="successNotification" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            <div class="flex items-center space-x-2">
                <span class="text-xl">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div id="errorNotification" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            <div class="flex items-center space-x-2">
                <span class="text-xl">❌</span>
                <span>{{ session('error') ?? $errors->first() }}</span>
            </div>
        </div>
    @endif

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
                                    
                                    @php
                                        $privacy = $user->privacy_settings ?? [];
                                    @endphp

                                    @if($user->career && ($privacy['show_career'] ?? true))
                                        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                            🎓 {{ $user->career }}
                                        </p>
                                    @endif
                                    
                                    @if($user->campus && ($privacy['show_campus'] ?? true))
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            🏛️ Campus {{ $user->campus }}
                                        </p>
                                    @endif

                                    @if($user->phone && ($privacy['show_phone'] ?? false))
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            📱 {{ $user->phone }}
                                        </p>
                                    @endif

                                    @if($user->email && ($privacy['show_email'] ?? false))
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            📧 {{ $user->email }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Calificación y Estadísticas -->
                                <div class="text-right">
                                    @if($user->hasReviews())
                                        @php
                                            $rating = $user->rating ?? 0;
                                            $count = $user->rating_count ?? 0;
                                        @endphp
                                        
                                        <!-- Estrellas visuales -->
                                        <div class="flex items-center justify-end space-x-1 mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($rating))
                                                    <span class="text-lg text-yellow-400">⭐</span>
                                                @elseif($i - $rating <= 0.5)
                                                    <span class="text-lg text-yellow-400">⭐</span>
                                                @else
                                                    <span class="text-lg text-gray-300 dark:text-gray-600">⭐</span>
                                                @endif
                                            @endfor
                                        </div>
                                        
                                        <!-- Promedio numérico -->
                                        <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            {{ number_format($rating, 1) }}/5.0
                                        </div>
                                        
                                        <!-- Conteo de reseñas -->
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Basado en {{ $count }} {{ $count == 1 ? 'reseña' : 'reseñas' }}
                                        </p>
                                        
                                        <!-- Enlace para ver todas las reseñas -->
                                        <a href="{{ route('reviews.show', $user) }}" 
                                           class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 underline">
                                            Ver todas las reseñas →
                                        </a>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
                                            <div class="text-gray-400 text-2xl mb-1">📝</div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                                Sin reseñas aún
                                            </p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                                Sé el primero en calificar
                                            </p>
                                        </div>
                                    @endif
                                </div>
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
                            
                            <!-- Estadísticas de Reseñas -->
                            @if($user->hasReviews())
                                <div class="mt-4 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">📊 Desglose de Reseñas</h3>
                                    
                                    @php
                                        $reviewStats = $user->reviews()->selectRaw('rating, COUNT(*) as count')->groupBy('rating')->orderBy('rating', 'desc')->get();
                                        $totalReviews = $user->rating_count;
                                    @endphp
                                    
                                    <div class="space-y-2">
                                        @for($star = 5; $star >= 1; $star--)
                                            @php
                                                $count = $reviewStats->where('rating', $star)->first()->count ?? 0;
                                                $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                            @endphp
                                            
                                            <div class="flex items-center space-x-2">
                                                <div class="flex items-center space-x-1 w-12">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ $star }}</span>
                                                    <span class="text-yellow-400">⭐</span>
                                                </div>
                                                
                                                <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                    <div class="h-full bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full transition-all duration-300"
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                                
                                                <div class="text-xs text-gray-600 dark:text-gray-400 w-12">
                                                    {{ $count }}
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    
                                    <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 text-center">
                                        Promedio: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ number_format($user->rating, 1) }}/5.0</span> 
                                        • Total: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $totalReviews }}</span> reseñas
                                    </div>
                                </div>
                            @endif

                            <!-- Acciones -->
                            @auth
                                @if(auth()->id() !== $user->id)
                                    <div class="mt-4 flex space-x-3">
                                        <button onclick="startConversation({{ $user->id }})"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                            💬 Enviar Mensaje
                                        </button>
                                        
                                        <button onclick="openReviewModal()"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition">
                                            ⭐ Dejar Reseña
                                        </button>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reseñas Recientes -->
            @if($user->hasReviews())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                ⭐ Reseñas Recientes
                            </h2>
                            <a href="{{ route('reviews.show', $user) }}" 
                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm underline">
                                Ver todas ({{ $user->rating_count }}) →
                            </a>
                        </div>

                        <div class="space-y-4">
                            @foreach($reviews as $review)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start space-x-4">
                                        <!-- Avatar del reviewer -->
                                        <div class="flex-shrink-0">
                                            @if($review->reviewer->profile_photo)
                                                <img src="{{ asset('storage/' . $review->reviewer->profile_photo) }}" 
                                                     alt="{{ $review->reviewer->name }}"
                                                     class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 dark:text-gray-300 text-sm font-semibold">
                                                        {{ substr($review->reviewer->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Contenido de la reseña -->
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $review->reviewer->name }}
                                                    </h4>
                                                    
                                                    <!-- Calificación -->
                                                    <div class="flex items-center space-x-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <span class="text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">
                                                                ⭐
                                                            </span>
                                                        @endfor
                                                        <span class="text-sm text-gray-600 dark:text-gray-400 ml-1">
                                                            ({{ $review->rating }}/5)
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-right">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $review->created_at->diffForHumans() }}
                                                    </p>
                                                    @if($review->listing)
                                                        <p class="text-xs text-blue-600 dark:text-blue-400">
                                                            Sobre: <a href="{{ route('listings.show', $review->listing) }}" class="underline">{{ Str::limit($review->listing->title, 20) }}</a>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Comentario -->
                                            @if($review->comment)
                                                <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mt-2">
                                                    "{{ $review->comment }}"
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($user->rating_count > 5)
                            <div class="mt-6 text-center">
                                <a href="{{ route('reviews.show', $user) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                    Ver todas las {{ $user->rating_count }} reseñas →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

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

    <!-- Modal para Dejar Reseña -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        ⭐ Dejar Reseña
                    </h3>
                    <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Información del usuario -->
                <div class="flex items-center space-x-3 mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex-shrink-0">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                 alt="{{ $user->name }}"
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 dark:text-gray-300 font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->university }} - {{ $user->career }}</p>
                    </div>
                </div>

                <!-- Formulario -->
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    
                    <!-- Selección de anuncio (opcional) -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Anuncio relacionado (opcional)
                        </label>
                        <select name="listing_id" 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Seleccionar anuncio...</option>
                            @foreach($user->listings()->where('status', '!=', 'draft')->latest()->limit(10)->get() as $listing)
                                <option value="{{ $listing->id }}">{{ Str::limit($listing->title, 50) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Calificación -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Calificación <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        onclick="setRating({{ $i }})"
                                        class="rating-star text-2xl text-gray-300 hover:text-yellow-400 transition-colors"
                                        data-rating="{{ $i }}">
                                    ⭐
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" required>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            Selecciona una calificación (requerido)
                        </p>
                    </div>

                    <!-- Comentario -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Comentario (opcional)
                        </label>
                        <textarea name="comment" 
                                  rows="4"
                                  maxlength="1000"
                                  placeholder="Cuenta tu experiencia con esta persona..."
                                  class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 resize-none"></textarea>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            0/1000 caracteres
                        </p>
                    </div>

                    <!-- Tip informativo -->
                    <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="text-yellow-400">💡</span>
                            </div>
                            <div class="ml-2">
                                <p class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">
                                    Tip: Las reseñas ayudan a otros usuarios a tomar decisiones informadas.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex space-x-3">
                        <button type="button" 
                                onclick="closeReviewModal()"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition">
                            Enviar Reseña
                        </button>
                    </div>
                </form>
            </div>
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

        // Modal de reseñas
        function openReviewModal() {
            const modal = document.getElementById('reviewModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }

        function closeReviewModal() {
            const modal = document.getElementById('reviewModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            // Resetear formulario
            document.getElementById('ratingInput').value = '';
            document.querySelectorAll('.rating-star').forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
        }

        // Sistema de calificación
        function setRating(rating) {
            document.getElementById('ratingInput').value = rating;
            
            // Actualizar estrellas visuales
            document.querySelectorAll('.rating-star').forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        // Contador de caracteres
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.querySelector('textarea[name="comment"]');
            if (textarea) {
                const counter = textarea.nextElementSibling;
                
                textarea.addEventListener('input', function() {
                    const current = this.value.length;
                    const max = 1000;
                    counter.textContent = `${current}/${max} caracteres`;
                    
                    if (current > max * 0.9) {
                        counter.classList.add('text-red-500');
                    } else {
                        counter.classList.remove('text-red-500');
                    }
                });
            }
        });

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

        // Auto-ocultar notificaciones flash
        document.addEventListener('DOMContentLoaded', function() {
            const successNotification = document.getElementById('successNotification');
            const errorNotification = document.getElementById('errorNotification');
            
            if (successNotification) {
                setTimeout(() => {
                    successNotification.style.opacity = '0';
                    setTimeout(() => successNotification.remove(), 300);
                }, 4000);
            }
            
            if (errorNotification) {
                setTimeout(() => {
                    errorNotification.style.opacity = '0';
                    setTimeout(() => errorNotification.remove(), 300);
                }, 5000);
            }
        });
    </script>
</x-app-layout>