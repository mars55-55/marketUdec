<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mi Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-blue-500">📦</div>
                            <div class="ml-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Mis Anuncios</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ auth()->user()->listings()->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-green-500">✅</div>
                            <div class="ml-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Activos</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ auth()->user()->listings()->where('status', 'active')->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-red-500">❤️</div>
                            <div class="ml-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Favoritos</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ auth()->user()->favorites()->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-3xl text-yellow-500">⭐</div>
                            <div class="ml-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Rating</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format(auth()->user()->rating, 1) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Acciones Rápidas</h3>
                        <div class="space-y-3">
                            <a href="{{ route('listings.create') }}" 
                               class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition">
                                <div class="text-2xl mr-3">➕</div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">Publicar Anuncio</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Vende algo nuevo</div>
                                </div>
                            </a>
                            
                            <a href="{{ route('favorites.index') }}" 
                               class="flex items-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                                <div class="text-2xl mr-3">❤️</div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">Ver Favoritos</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Anuncios guardados</div>
                                </div>
                            </a>
                            
                            <a href="{{ route('conversations.index') }}" 
                               class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition">
                                <div class="text-2xl mr-3">💬</div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">Mensajes</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Conversaciones activas</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Mis Anuncios Recientes</h3>
                        <div class="space-y-3">
                            @forelse(auth()->user()->listings()->latest()->limit(3)->get() as $listing)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                            {{ $listing->title }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            ${{ number_format($listing->price, 0) }} • {{ $listing->status }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('listings.show', $listing) }}" 
                                           class="text-blue-600 hover:text-blue-500">Ver</a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                    No tienes anuncios aún. 
                                    <a href="{{ route('listings.create') }}" class="text-blue-600 hover:text-blue-500">¿Crear uno?</a>
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
