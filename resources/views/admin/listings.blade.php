@extends('admin.layout')

@section('title', 'Gestión de Anuncios')

@section('content')
<div class="space-y-6">
    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <span class="text-2xl">📦</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Total Anuncios</h3>
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
                <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                    <span class="text-2xl">🚫</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Bloqueados</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['blocked'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <span class="text-2xl">💰</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Vendidos</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['sold'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Título, descripción o usuario..."
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700">
            </div>

            <div class="min-w-48">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                <select name="status" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Vendido</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Bloqueado</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                </select>
            </div>

            <div class="min-w-48">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoría</label>
                <select name="category" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                Buscar
            </button>
        </form>
    </div>

    <!-- Lista de Anuncios -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Anuncios</h2>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($listings as $listing)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex items-start space-x-4">
                        <!-- Imagen -->
                        <div class="flex-shrink-0">
                            @if($listing->images->first())
                                <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}" 
                                     alt="{{ $listing->title }}"
                                     class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-400 text-xl">📦</span>
                                </div>
                            @endif
                        </div>

                        <!-- Información -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                                        {{ $listing->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        {{ Str::limit($listing->description, 100) }}
                                    </p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span>👤 {{ $listing->user->name }}</span>
                                        <span>🏷️ {{ $listing->category->name }}</span>
                                        <span>📅 {{ $listing->created_at->format('d/m/Y') }}</span>
                                        @if($listing->reports_count > 0)
                                            <span class="text-red-600 font-medium">🚨 {{ $listing->reports_count }} reportes</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-2xl font-bold text-green-600 mb-2">
                                        ${{ number_format($listing->price) }}
                                    </p>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $listing->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $listing->status == 'sold' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $listing->status == 'blocked' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $listing->status == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($listing->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex items-center space-x-2 mt-3">
                                <a href="{{ route('listings.show', $listing) }}" target="_blank" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition">
                                    👁️ Ver
                                </a>

                                @if($listing->status != 'blocked')
                                    <form action="{{ route('admin.listings.block', $listing) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition"
                                                onclick="return confirm('¿Estás seguro de bloquear este anuncio?')">
                                            🚫 Bloquear
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.listings.unblock', $listing) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">
                                            ✅ Desbloquear
                                        </button>
                                    </form>
                                @endif

                                @if($listing->reports_count > 0)
                                    <a href="{{ route('admin.reports', ['listing_id' => $listing->id]) }}" 
                                       class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded text-sm transition">
                                        🚨 Ver Reportes ({{ $listing->reports_count }})
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">📭</div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No hay anuncios</h3>
                    <p class="text-gray-600 dark:text-gray-400">No se encontraron anuncios con los filtros seleccionados.</p>
                </div>
            @endforelse
        </div>

        @if($listings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $listings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection