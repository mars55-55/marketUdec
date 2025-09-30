@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Reportes Pendientes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gradient-to-br from-red-100 to-orange-100 text-red-600">
                    <span class="text-2xl">🚨</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Reportes Pendientes</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingReports }}</p>
                    @if($pendingReports > 0)
                        <p class="text-xs text-red-600">Requiere atención</p>
                    @else
                        <p class="text-xs text-green-600">Todo al día</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Total de Anuncios -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 text-blue-600">
                    <span class="text-2xl">📦</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Anuncios</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalListings }}</p>
                    <p class="text-xs text-gray-500">En la plataforma</p>
                </div>
            </div>
        </div>

        <!-- Total de Usuarios -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gradient-to-br from-green-100 to-emerald-100 text-green-600">
                    <span class="text-2xl">👥</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Usuarios</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-500">Registrados</p>
                </div>
            </div>
        </div>

        <!-- Anuncios Activos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gradient-to-br from-yellow-100 to-amber-100 text-yellow-600">
                    <span class="text-2xl">✅</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Anuncios Activos</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $activeListings }}</p>
                    <p class="text-xs text-gray-500">Publicados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.reports') }}" class="block bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 text-white hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-lg">
            <div class="flex items-center space-x-3">
                <span class="text-3xl">🚨</span>
                <div>
                    <h3 class="text-lg font-semibold">Revisar Reportes</h3>
                    <p class="text-sm opacity-90">Moderar contenido reportado</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.listings') }}" class="block bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl p-6 text-white hover:from-green-700 hover:to-emerald-700 transition-all transform hover:scale-105 shadow-lg">
            <div class="flex items-center space-x-3">
                <span class="text-3xl">📦</span>
                <div>
                    <h3 class="text-lg font-semibold">Gestionar Anuncios</h3>
                    <p class="text-sm opacity-90">Administrar publicaciones</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.users') }}" class="block bg-gradient-to-r from-orange-600 to-red-600 rounded-xl p-6 text-white hover:from-orange-700 hover:to-red-700 transition-all transform hover:scale-105 shadow-lg">
            <div class="flex items-center space-x-3">
                <span class="text-3xl">👥</span>
                <div>
                    <h3 class="text-lg font-semibold">Gestionar Usuarios</h3>
                    <p class="text-sm opacity-90">Administrar cuentas</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Reportes Recientes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-750">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                    <span>📊</span>
                    <span>Reportes Recientes</span>
                </h3>
                <a href="{{ route('admin.reports') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                    Ver todos →
                </a>
            </div>
        </div>
        
        @if($recentReports->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-750">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Anuncio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Motivo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentReports as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ substr($report->reporter->name, 0, 1) }}</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->reporter->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">{{ Str::limit($report->listing->title, 30) }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $report->listing->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full">
                                        {{ ucfirst($report->reason) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($report->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                            ⏳ Pendiente
                                        </span>
                                    @elseif($report->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            ✅ Aprobado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            ❌ Rechazado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $report->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.reports') }}?report={{ $report->id }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">
                                        Ver detalles
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">🎉</div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">¡No hay reportes!</h3>
                <p class="text-gray-500 dark:text-gray-400">La comunidad está behaving well. Todo está tranquilo por aquí.</p>
            </div>
        @endif
    </div>
@endsection