@extends('admin.layout')

@section('title', 'Gestión de Categorías')

@section('content')
<div class="space-y-6">
    <!-- Crear Nueva Categoría -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Crear Nueva Categoría</h2>
        
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex gap-4 items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre</label>
                <input type="text" name="name" required
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700"
                       placeholder="Nombre de la categoría">
            </div>
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                <input type="text" name="description"
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700"
                       placeholder="Descripción opcional">
            </div>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">
                ➕ Crear
            </button>
        </form>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <span class="text-2xl">🏷️</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Categorías</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <span class="text-2xl">📦</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Con Anuncios</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['with_listings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <span class="text-2xl">📈</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Más Popular</h3>
                    <p class="text-lg font-bold text-purple-600">{{ $stats['most_popular']->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Categorías -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Categorías Existentes</h2>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($categories as $category)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ $category->name }}
                                </h3>
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ $category->listings_count }} anuncios
                                </span>
                            </div>
                            
                            @if($category->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    {{ $category->description }}
                                </p>
                            @endif
                            
                            <p class="text-xs text-gray-500">
                                Creada: {{ $category->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- Formulario de edición inline -->
                            <button onclick="toggleEdit({{ $category->id }})" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition">
                                ✏️ Editar
                            </button>

                            @if($category->listings_count == 0)
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition"
                                            onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-500">No se puede eliminar<br>(tiene anuncios)</span>
                            @endif
                        </div>
                    </div>

                    <!-- Formulario de edición (oculto por defecto) -->
                    <div id="edit-form-{{ $category->id }}" class="hidden mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex gap-4 items-end">
                            @csrf
                            @method('PUT')
                            
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre</label>
                                <input type="text" name="name" value="{{ $category->name }}" required
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600">
                            </div>
                            
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                                <input type="text" name="description" value="{{ $category->description }}"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600">
                            </div>
                            
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                💾 Guardar
                            </button>
                            
                            <button type="button" onclick="toggleEdit({{ $category->id }})"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                                ❌ Cancelar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">🏷️</div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No hay categorías</h3>
                    <p class="text-gray-600 dark:text-gray-400">Crea la primera categoría para organizar los anuncios.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function toggleEdit(categoryId) {
    const form = document.getElementById(`edit-form-${categoryId}`);
    form.classList.toggle('hidden');
}
</script>
@endsection