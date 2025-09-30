<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Marketplace UdeC') }}
            </h2>
            @auth
                <a href="{{ route('listings.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Publicar Anuncio
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('search') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Búsqueda por palabra clave -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Buscar
                                </label>
                                <input type="text" 
                                       name="q" 
                                       value="{{ request('q') }}"
                                       placeholder="¿Qué buscas?"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <!-- Filtro por categoría -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Categoría
                                </label>
                                <select name="category" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->icon }} {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Rango de precio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Precio mínimo
                                </label>
                                <input type="number" 
                                       name="min_price" 
                                       value="{{ request('min_price') }}"
                                       placeholder="$0"
                                       min="0"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Precio máximo
                                </label>
                                <input type="number" 
                                       name="max_price" 
                                       value="{{ request('max_price') }}"
                                       placeholder="Sin límite"
                                       min="0"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <!-- Estado del anuncio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Disponibilidad
                                </label>
                                <select name="status" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">Ver todos</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Solo disponibles</option>
                                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Solo vendidos</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Buscar
                            </button>
                            
                            @if(request()->hasAny(['q', 'category', 'min_price', 'max_price', 'status']))
                                <a href="{{ route('home') }}" 
                                   class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                                    Limpiar filtros
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados -->
            @if($listings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($listings as $listing)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                            <div class="relative">
                                <!-- Imagen -->
                                <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-700 {{ $listing->status === 'sold' ? 'relative' : '' }}">
                                    @if($listing->primaryImage)
                                        <img src="{{ asset('storage/' . $listing->primaryImage->image_path) }}" 
                                             alt="{{ $listing->title }}"
                                             class="w-full h-48 object-cover {{ $listing->status === 'sold' ? 'opacity-60 grayscale' : '' }}">
                                    @else
                                        <div class="w-full h-48 flex items-center justify-center bg-gray-100 dark:bg-gray-700 {{ $listing->status === 'sold' ? 'opacity-60' : '' }}">
                                            <span class="text-4xl">{{ $listing->category->icon ?? '📦' }}</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Overlay de vendido -->
                                    @if($listing->status === 'sold')
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                            <div class="bg-red-500 text-white px-4 py-2 rounded-lg font-bold text-lg shadow-lg transform rotate-12">
                                                ✓ VENDIDO
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Botón de favorito -->
                                @auth
                                    @if($listing->status === 'active')
                                        <button class="absolute top-2 right-2 favorite-btn bg-white dark:bg-gray-800 rounded-full p-2 shadow-md hover:shadow-lg transition"
                                                data-listing-id="{{ $listing->id }}"
                                                data-is-favorite="{{ $listing->isFavoritedBy(auth()->user()) ? 'true' : 'false' }}">
                                            <span class="favorite-icon text-lg">
                                                {{ $listing->isFavoritedBy(auth()->user()) ? '❤️' : '🤍' }}
                                            </span>
                                        </button>
                                    @else
                                        <div class="absolute top-2 right-2 bg-gray-400 rounded-full p-2 shadow-md opacity-50">
                                            <span class="text-lg">💔</span>
                                        </div>
                                    @endif
                                @endauth
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
                                    <span class="text-2xl font-bold {{ $listing->status === 'sold' ? 'text-gray-500 dark:text-gray-400 line-through' : 'text-green-600 dark:text-green-400' }}">
                                        ${{ number_format($listing->price, 0) }}
                                    </span>
                                    <div class="flex items-center space-x-2">
                                        @if($listing->status === 'sold')
                                            <span class="text-xs text-white bg-red-500 px-2 py-1 rounded font-bold">
                                                VENDIDO
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                            {{ ucfirst($listing->condition) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                                    <span>Por {{ $listing->user->name }}</span>
                                    <span>{{ $listing->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <span>👁️ {{ $listing->views }}</span>
                                        @if($listing->location)
                                            <span class="ml-2">📍 {{ $listing->location }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if($listing->status === 'sold')
                                    <div class="mt-4 space-y-2">
                                        <div class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-center font-medium py-2 px-4 rounded">
                                            🙅‍♂️ No disponible
                                        </div>
                                        <a href="{{ route('listings.show', $listing) }}" 
                                           class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-center font-bold py-1 px-4 rounded transition text-sm">
                                            Ver anuncio
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('listings.show', $listing) }}" 
                                       class="block w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded transition">
                                        Ver Detalles
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-8">
                    {{ $listings->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            No se encontraron anuncios
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Intenta cambiar los filtros de búsqueda o 
                            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-500">ver todos los anuncios</a>
                        </p>
                        @auth
                            <a href="{{ route('listings.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ¿Publicar el primer anuncio?
                            </a>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>

    @auth
    <script>
        // Funcionalidad de favoritos
        document.addEventListener('DOMContentLoaded', function() {
            const favoriteButtons = document.querySelectorAll('.favorite-btn');
            
            favoriteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const listingId = this.dataset.listingId;
                    const icon = this.querySelector('.favorite-icon');
                    
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
                            this.dataset.isFavorite = data.is_favorite;
                            
                            // Mostrar mensaje temporal
                            const message = document.createElement('div');
                            message.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
                            message.textContent = data.message;
                            document.body.appendChild(message);
                            
                            setTimeout(() => {
                                message.remove();
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        });
    </script>
    @endauth
</x-app-layout>