@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="text-9xl mb-4">🔒</div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Acceso Denegado
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                No tienes permisos para acceder a esta página
            </p>
        </div>
        
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center space-x-2">
                <span class="text-red-600 dark:text-red-400">⚠️</span>
                <p class="text-sm text-red-700 dark:text-red-300">
                    Esta sección requiere permisos de administrador.
                </p>
            </div>
        </div>

        <div class="flex flex-col space-y-4">
            <a href="{{ route('home') }}" 
               class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                <span class="flex items-center space-x-2">
                    <span>🏠</span>
                    <span>Volver al Inicio</span>
                </span>
            </a>
            
            <a href="{{ route('profile.edit') }}" 
               class="group relative w-full flex justify-center py-3 px-4 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                <span class="flex items-center space-x-2">
                    <span>👤</span>
                    <span>Mi Perfil</span>
                </span>
            </a>
        </div>

        <div class="mt-8 text-xs text-gray-500 dark:text-gray-400">
            <p>Si crees que esto es un error, contacta al administrador del sistema.</p>
        </div>
    </div>
</div>
@endsection