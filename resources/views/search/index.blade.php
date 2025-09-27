<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Búsqueda') }}
                @if(request('q'))
                    - "{{ request('q') }}"
                @endif
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
            <!-- Filtros Avanzados -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('search') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Búsqueda -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    ¿Qué buscas?
                                </label>
                                <input type="text" 
                                       name="q" 
                                       value="{{ request('q') }}"
                                       placeholder="Ej: libro cálculo, laptop, guitarra..."
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Categoría
                                </label>
                                <select name="category" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">Todas</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->icon }} {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ordenar -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Ordenar por
                                </label>
                                <select name="sort" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Más recientes</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Precio menor</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Precio mayor</option>
                                    <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Más vistos</option>
                                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>A-Z</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtros adicionales -->
                        <details class="border-t pt-4">
                            <summary class="cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                Filtros avanzados
                            </summary>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <!-- Precio -->
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

                                <!-- Condición -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Estado
                                    </label>
                                    <select name="condition" 
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="">Cualquiera</option>
                                        <option value="nuevo" {{ request('condition') == 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                                        <option value="como_nuevo" {{ request('condition') == 'como_nuevo' ? 'selected' : '' }}>Como nuevo</option>
                                        <option value="bueno" {{ request('condition') == 'bueno' ? 'selected' : '' }}>Bueno</option>
                                        <option value="aceptable" {{ request('condition') == 'aceptable' ? 'selected' : '' }}>Aceptable</option>
                                        <option value="malo" {{ request('condition') == 'malo' ? 'selected' : '' }}>Malo</option>
                                    </select>
                                </div>

                                <!-- Ubicación -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Ubicación
                                    </label>
                                    <input type="text" 
                                           name="location" 
                                           value="{{ request('location') }}"
                                           placeholder="Ej: Concepción"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                            </div>
                        </details>

                        <div class="flex justify-between items-center pt-4 border-t">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                🔍 Buscar
                            </button>
                            
                            @if(request()->hasAny(['q', 'category', 'min_price', 'max_price', 'condition', 'location', 'sort']))
                                <a href="{{ route('search') }}" 
                                   class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                                    Limpiar filtros
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados -->
            @if($totalResults > 0)
                <div class="mb-6">
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ $totalResults }} {{ $totalResults == 1 ? 'resultado encontrado' : 'resultados encontrados' }}
                        @if(request('q'))
                            para "<strong>{{ request('q') }}</strong>"
                        @endif
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($listings as $listing)
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

                                <!-- Botón de favorito -->
                                @auth
                                    <button class="absolute top-2 right-2 favorite-btn bg-white dark:bg-gray-800 rounded-full p-2 shadow-md hover:shadow-lg transition"
                                            data-listing-id="{{ $listing->id }}"
                                            data-is-favorite="{{ $listing->isFavoritedBy(auth()->user()) ? 'true' : 'false' }}">
                                        <span class="favorite-icon text-lg">
                                            {{ $listing->isFavoritedBy(auth()->user()) ? '❤️' : '🤍' }}
                                        </span>
                                    </button>
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
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            No se encontraron resultados
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            @if(request('q'))
                                No hay anuncios que coincidan con "<strong>{{ request('q') }}</strong>".
                            @else
                                No se encontraron anuncios con los filtros seleccionados.
                            @endif
                            <br>
                            Intenta con otros términos de búsqueda o 
                            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-500">explora todos los anuncios</a>.
                        </p>
                        @auth
                            <a href="{{ route('listings.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ¿Ser el primero en publicar?
                            </a>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>

    @auth
    <script>
        // Funcionalidad de favoritos (reutilizada)
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