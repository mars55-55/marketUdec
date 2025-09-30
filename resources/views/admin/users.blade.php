@extends('admin.layout')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="space-y-6">
    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <span class="text-2xl">👥</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Total Usuarios</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <span class="text-2xl">✅</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Activos</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <span class="text-2xl">📦</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Con Anuncios</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['with_listings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <span class="text-2xl">📅</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Nuevos (7d)</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['new'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o email..."
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                Buscar
            </button>
        </form>
    </div>

    <!-- Lista de Usuarios -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Usuarios</h2>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($users as $user)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     alt="{{ $user->name }}"
                                     class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $user->email }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $user->university }} - {{ $user->career }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Miembro desde</p>
                                    <p class="font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                <span>📦 {{ $user->listings_count }} anuncios</span>
                                <span>⭐ {{ number_format($user->rating, 1) }}/5.0 ({{ $user->rating_count }} reseñas)</span>
                                <span>📅 Último acceso: {{ $user->updated_at->diffForHumans() }}</span>
                            </div>

                            <div class="flex items-center space-x-2 mt-3">
                                <a href="{{ route('users.show', $user) }}" target="_blank" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition">
                                    👁️ Ver Perfil
                                </a>
                                
                                <a href="{{ route('admin.listings', ['user' => $user->id]) }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">
                                    📦 Ver Anuncios ({{ $user->listings_count }})
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">👤</div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No hay usuarios</h3>
                    <p class="text-gray-600 dark:text-gray-400">No se encontraron usuarios con los filtros seleccionados.</p>
                </div>
            @endforelse
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection