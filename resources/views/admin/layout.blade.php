<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Panel de Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('admin.index') }}" class="flex items-center space-x-2">
                                <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-2 rounded-lg">
                                    <span class="text-white font-bold text-xl">🛡️</span>
                                </div>
                                <span class="text-xl font-bold text-gray-900 dark:text-white">MarketUdeC</span>
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full font-medium">ADMIN</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                                <span class="flex items-center space-x-1">
                                    <span>📊</span>
                                    <span>{{ __('Dashboard') }}</span>
                                </span>
                            </x-nav-link>
                            
                            <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">
                                <span class="flex items-center space-x-1">
                                    <span>🚨</span>
                                    <span>{{ __('Reportes') }}</span>
                                </span>
                            </x-nav-link>
                            
                            <x-nav-link :href="route('admin.listings')" :active="request()->routeIs('admin.listings')">
                                <span class="flex items-center space-x-1">
                                    <span>�</span>
                                    <span>{{ __('Anuncios') }}</span>
                                </span>
                            </x-nav-link>
                            
                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                                <span class="flex items-center space-x-1">
                                    <span>👥</span>
                                    <span>{{ __('Usuarios') }}</span>
                                </span>
                            </x-nav-link>
                            
                            <x-nav-link :href="route('admin.categories')" :active="request()->routeIs('admin.categories')">
                                <span class="flex items-center space-x-1">
                                    <span>🏷️</span>
                                    <span>{{ __('Categorías') }}</span>
                                </span>
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Notification Badge -->
                        @php
                            $pendingReports = \App\Models\Report::where('status', 'pending')->count();
                        @endphp
                        
                        @if($pendingReports > 0)
                            <a href="{{ route('admin.reports') }}" class="relative mr-4 p-2 text-orange-600 hover:text-orange-800 transition">
                                <span class="text-xl">�</span>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                    {{ $pendingReports }}
                                </span>
                            </a>
                        @endif

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                        <span>{{ Auth::user()->name }}</span>
                                    </div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('home')" class="flex items-center space-x-2">
                                    <span>🏠</span>
                                    <span>{{ __('Volver al Sitio') }}</span>
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center space-x-2">
                                    <span>⚙️</span>
                                    <span>{{ __('Configuración') }}</span>
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                            class="flex items-center space-x-2">
                                        <span>🔴</span>
                                        <span>{{ __('Cerrar Sesión') }}</span>
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                        📊 {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">
                        🚨 {{ __('Reportes') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.listings')" :active="request()->routeIs('admin.listings')">
                        📦 {{ __('Anuncios') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                        👥 {{ __('Usuarios') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories')" :active="request()->routeIs('admin.categories')">
                        🏷️ {{ __('Categorías') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <div class="mb-6">
                    <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                        <a href="{{ route('admin.index') }}" class="hover:text-purple-600">🏠 Admin</a>
                        <span>›</span>
                        <span class="text-gray-900 dark:text-gray-100 font-medium">@yield('title', 'Panel de Administración')</span>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <span class="text-xl">✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <span class="text-xl">❌</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Page Title -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        @yield('title', 'Panel de Administración')
                    </h1>
                </div>

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>