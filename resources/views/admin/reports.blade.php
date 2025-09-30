@extends('admin.layout')

@section('title', 'Gestión de Reportes')

@section('content')
<div class="space-y-6">
    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                    <span class="text-2xl">🚨</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reportes Pendientes</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['pending_reports'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <span class="text-2xl">⏳</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Revisados</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['reviewed_reports'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <span class="text-2xl">✅</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Resueltos Hoy</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['resolved_today'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                <select name="status" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Revisados</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resuelto</option>
                </select>
            </div>

            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo</label>
                <select name="type" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700">
                    <option value="">Todos los tipos</option>
                    <option value="spam" {{ request('type') == 'spam' ? 'selected' : '' }}>Spam</option>
                    <option value="inappropriate" {{ request('type') == 'inappropriate' ? 'selected' : '' }}>Contenido Inapropiado</option>
                    <option value="scam" {{ request('type') == 'scam' ? 'selected' : '' }}>Estafa</option>
                    <option value="fake" {{ request('type') == 'fake' ? 'selected' : '' }}>Producto Falso</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Lista de Reportes -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Reportes Recientes</h2>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($reports as $report)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $report->status == 'pending' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $report->status == 'reviewed' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $report->status == 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $report->status == 'dismissed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $report->type)) }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $report->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <!-- Información del Reporte -->
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Reportado por: {{ $report->reporter->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        <strong>Razón:</strong> {{ $report->reason }}
                                    </p>
                                    @if($report->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <strong>Descripción:</strong> {{ $report->description }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Información del Anuncio Reportado -->
                                @if($report->listing)
                                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                                        <div class="flex items-center space-x-3">
                                            @if($report->listing->images->first())
                                                <img src="{{ asset('storage/' . $report->listing->images->first()->image_path) }}" 
                                                     alt="{{ $report->listing->title }}"
                                                     class="w-12 h-12 object-cover rounded-lg">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-gray-400">📦</span>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ Str::limit($report->listing->title, 30) }}
                                                </h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    Por: {{ $report->listing->user->name }}
                                                </p>
                                                <p class="text-sm font-medium text-green-600">
                                                    ${{ number_format($report->listing->price) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Acciones de Moderación -->
                            <div class="flex items-center space-x-2 mt-4">
                                @if($report->status == 'pending')
                                    <form action="{{ route('admin.reports.review', $report) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm transition">
                                            Marcar en Revisión
                                        </button>
                                    </form>
                                @endif

                                @if($report->listing && $report->listing->status != 'blocked')
                                    <form action="{{ route('admin.listings.block', $report->listing) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition"
                                                onclick="return confirm('¿Estás seguro de bloquear esta publicación?')">
                                            🚫 Bloquear Anuncio
                                        </button>
                                    </form>
                                @endif

                                @if($report->listing && $report->listing->status == 'blocked')
                                    <form action="{{ route('admin.listings.unblock', $report->listing) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">
                                            ✅ Desbloquear Anuncio
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.reports.resolve', $report) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">
                                        ✅ Aprobar (Sin Infracción)
                                    </button>
                                </form>

                                <a href="{{ route('listings.show', $report->listing) }}" target="_blank" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition">
                                    👁️ Ver Anuncio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">📭</div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No hay reportes</h3>
                    <p class="text-gray-600 dark:text-gray-400">No se encontraron reportes con los filtros seleccionados.</p>
                </div>
            @endforelse
        </div>

        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $reports->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection