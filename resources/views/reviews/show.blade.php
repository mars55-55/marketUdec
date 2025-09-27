<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ⭐ {{ __('Reseñas de') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Resumen de calificaciones -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     alt="{{ $user->name }}"
                                     class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <span class="text-gray-600 dark:text-gray-300 text-xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            
                            <div>
                                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $user->name }}
                                </h1>
                                <a href="{{ route('users.show', $user) }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                    ← Ver perfil completo
                                </a>
                            </div>
                        </div>

                        @if($user->rating > 0)
                            <div class="text-center">
                                <div class="flex items-center justify-center space-x-1 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-2xl {{ $i <= $user->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                            ⭐
                                        </span>
                                    @endfor
                                </div>
                                <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format($user->rating, 1) }}/5
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $reviews->total() }} {{ $reviews->total() == 1 ? 'reseña' : 'reseñas' }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Distribución de calificaciones -->
                    @if($reviews->total() > 0)
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-4">
                            @for($rating = 5; $rating >= 1; $rating--)
                                @php
                                    $count = $reviews->where('rating', $rating)->count();
                                    $percentage = $reviews->total() > 0 ? ($count / $reviews->total()) * 100 : 0;
                                @endphp
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 w-8">{{ $rating }}⭐</span>
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 w-8">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lista de reseñas -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="border-b border-gray-200 dark:border-gray-600 pb-6 last:border-b-0">
                                    <div class="flex items-start space-x-4">
                                        <!-- Avatar del reviewer -->
                                        <div class="flex-shrink-0">
                                            @if($review->reviewer->profile_photo)
                                                <img src="{{ asset('storage/' . $review->reviewer->profile_photo) }}" 
                                                     alt="{{ $review->reviewer->name }}"
                                                     class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <div class="w-12 h-12 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 dark:text-gray-300 font-semibold">
                                                        {{ substr($review->reviewer->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $review->reviewer->name }}
                                                    </h4>
                                                    <div class="flex items-center space-x-1 mt-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <span class="text-lg {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">
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
                                                <p class="text-gray-700 dark:text-gray-300 mb-3">
                                                    {{ $review->comment }}
                                                </p>
                                            @endif

                                            @if($review->listing)
                                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                    <div class="flex items-center space-x-3">
                                                        @if($review->listing->primaryImage)
                                                            <img src="{{ asset('storage/' . $review->listing->primaryImage->image_path) }}" 
                                                                 alt="{{ $review->listing->title }}"
                                                                 class="w-12 h-12 rounded object-cover">
                                                        @else
                                                            <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                                                <span class="text-lg">{{ $review->listing->category->icon ?? '📦' }}</span>
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="flex-1">
                                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $review->listing->title }}
                                                            </p>
                                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                                ${{ number_format($review->listing->price, 0) }}
                                                            </p>
                                                        </div>
                                                        
                                                        <a href="{{ route('listings.show', $review->listing) }}" 
                                                           class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                                            Ver anuncio →
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-8">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">⭐</div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                {{ $user->name }} no tiene reseñas aún
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                Sé el primero en dejar una reseña después de hacer una transacción.
                            </p>
                            <a href="{{ route('users.show', $user) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ver perfil de {{ $user->name }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>