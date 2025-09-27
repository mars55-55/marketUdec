<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🛡️ {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Estadísticas generales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl mb-2">👥</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ number_format($stats['users']) }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Usuarios</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl mb-2">📦</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ number_format($stats['listings']) }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Anuncios</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl mb-2">✅</div>
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($stats['active_listings']) }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Activos</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl mb-2">🚨</div>
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                            {{ number_format($stats['reports']) }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Reportes</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl mb-2">⭐</div>
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                            {{ number_format($stats['reviews']) }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Reseñas</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl mb-2">🏷️</div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($stats['categories']) }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Categorías</div>
                    </div>
                </div>
            </div>

            <!-- Navegación rápida -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        🚀 Acciones Rápidas
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('admin.reports') }}" 
                           class="flex items-center space-x-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                            <span class="text-2xl">🚨</span>
                            <div>
                                <div class="font-medium text-red-900 dark:text-red-100">Reportes Pendientes</div>
                                <div class="text-sm text-red-600 dark:text-red-400">{{ $stats['reports'] }} sin revisar</div>
                            </div>
                        </a>

                        <a href="{{ route('admin.listings') }}" 
                           class="flex items-center space-x-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition">
                            <span class="text-2xl">📦</span>
                            <div>
                                <div class="font-medium text-blue-900 dark:text-blue-100">Gestionar Anuncios</div>
                                <div class="text-sm text-blue-600 dark:text-blue-400">Moderar contenido</div>
                            </div>
                        </a>

                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center space-x-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition">
                            <span class="text-2xl">👥</span>
                            <div>
                                <div class="font-medium text-green-900 dark:text-green-100">Usuarios</div>
                                <div class="text-sm text-green-600 dark:text-green-400">Ver y gestionar</div>
                            </div>
                        </a>

                        <a href="{{ route('admin.categories') }}" 
                           class="flex items-center space-x-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition">
                            <span class="text-2xl">🏷️</span>
                            <div>
                                <div class="font-medium text-purple-900 dark:text-purple-100">Categorías</div>
                                <div class="text-sm text-purple-600 dark:text-purple-400">Crear y editar</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Reportes recientes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                🚨 Reportes Recientes
                            </h3>
                            <a href="{{ route('admin.reports') }}" 
                               class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                Ver todos →
                            </a>
                        </div>

                        @if($recentReports->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentReports as $report)
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex-shrink-0">
                                            <span class="text-lg">
                                                @switch($report->reason)
                                                    @case('inappropriate')
                                                        🔞
                                                        @break
                                                    @case('spam')
                                                        📧
                                                        @break
                                                    @case('fraud')
                                                        ⚠️
                                                        @break
                                                    @case('fake')
                                                        🎭
                                                        @break
                                                    @case('other')
                                                        ❓
                                                        @break
                                                    @default
                                                        🚨
                                                @endswitch
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ ucfirst($report->reason) }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                                @if($report->listing)
                                                    Anuncio: {{ $report->listing->title }}
                                                @elseif($report->reportedUser)
                                                    Usuario: {{ $report->reportedUser->name }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Por {{ $report->reporter->name }} • {{ $report->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-2">✅</div>
                                <p class="text-gray-600 dark:text-gray-400">No hay reportes pendientes</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Usuarios más activos -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                🏆 Usuarios Más Activos
                            </h3>
                            <a href="{{ route('admin.users') }}" 
                               class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                Ver todos →
                            </a>
                        </div>

                        <div class="space-y-3">
                            @foreach($activeUsers as $user)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                                 alt="{{ $user->name }}"
                                                 class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                <span class="text-gray-600 dark:text-gray-300 text-xs font-semibold">
                                                    {{ substr($user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $user->listings_count }} anuncios • {{ $user->reviews_count }} reseñas
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                        Ver →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Anuncios recientes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            📦 Anuncios Recientes
                        </h3>
                        <a href="{{ route('admin.listings') }}" 
                           class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                            Ver todos →
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Anuncio
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Usuario
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Categoría
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Precio
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($recentListings as $listing)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-lg mr-2">{{ $listing->category->icon ?? '📦' }}</span>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ Str::limit($listing->title, 30) }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        ID: {{ $listing->id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $listing->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $listing->category->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            ${{ number_format($listing->price, 0) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $listing->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($listing->status === 'sold' ? 'bg-gray-100 text-gray-800' : 
                                                    ($listing->status === 'paused' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                                {{ ucfirst($listing->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $listing->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>