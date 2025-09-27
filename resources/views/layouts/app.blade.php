<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') . ' - Marketplace Universitario' }}</title>
        <meta name="description" content="MarketUdeC - El marketplace oficial para estudiantes universitarios. Compra, vende e intercambia productos con seguridad.">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Styles -->
        <style>
            body { font-family: 'Inter', sans-serif; }
            
            /* Smooth transitions */
            * {
                @apply transition-colors duration-200;
            }
            
            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            
            ::-webkit-scrollbar-track {
                @apply bg-gray-100 dark:bg-gray-800;
            }
            
            ::-webkit-scrollbar-thumb {
                @apply bg-gray-300 dark:bg-gray-600 rounded-full;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                @apply bg-gray-400 dark:bg-gray-500;
            }

            /* Loading animation */
            @keyframes pulse-scale {
                0%, 100% { transform: scale(1); opacity: 1; }
                50% { transform: scale(1.05); opacity: 0.8; }
            }
            
            .animate-pulse-scale {
                animation: pulse-scale 2s ease-in-out infinite;
            }

            /* Gradient backgrounds */
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            
            .gradient-bg-warm {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }
            
            .gradient-bg-cool {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }

            /* Card hover effects */
            .hover-lift {
                @apply transition-all duration-300 ease-in-out;
            }
            
            .hover-lift:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            /* Glass morphism effect */
            .glass {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.75);
                border: 1px solid rgba(209, 213, 219, 0.3);
            }
            
            .glass-dark {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(17, 24, 39, 0.75);
                border: 1px solid rgba(75, 85, 99, 0.3);
            }

            /* Button enhancements */
            .btn-primary {
                @apply bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200;
            }
            
            .btn-secondary {
                @apply bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-2.5 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200;
            }

            /* Notification styles */
            .notification {
                @apply fixed top-4 right-4 z-50 max-w-sm w-full bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700 transform transition-all duration-300 ease-in-out;
            }
            
            .notification.show {
                @apply translate-x-0 opacity-100;
            }
            
            .notification.hide {
                @apply translate-x-full opacity-0;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">
        <!-- Background Pattern -->
        <div class="fixed inset-0 opacity-30 dark:opacity-20">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(99, 102, 241, 0.15) 1px, transparent 0); background-size: 30px 30px;"></div>
        </div>

        <div class="relative min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="glass dark:glass-dark shadow-lg border-b border-white/20 dark:border-gray-700/20 sticky top-16 z-40">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative z-10">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="glass dark:glass-dark mt-20 border-t border-white/20 dark:border-gray-700/20">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="col-span-1 md:col-span-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">🎓</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">MarketUdeC</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Marketplace Universitario</p>
                                </div>
                            </div>
                            <p class="mt-4 text-gray-600 dark:text-gray-400 max-w-md">
                                La plataforma oficial para que estudiantes universitarios compren, vendan e intercambien productos de manera segura y confiable.
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Enlaces</h4>
                            <ul class="mt-4 space-y-2">
                                <li><a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Inicio</a></li>
                                <li><a href="{{ route('search') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Buscar</a></li>
                                @auth
                                    <li><a href="{{ route('listings.create') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Publicar</a></li>
                                @endauth
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Soporte</h4>
                            <ul class="mt-4 space-y-2">
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Centro de Ayuda</a></li>
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Términos de Uso</a></li>
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Privacidad</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-center text-gray-500 dark:text-gray-400 text-sm">
                            © {{ date('Y') }} MarketUdeC. Todos los derechos reservados. 
                            <span class="inline-block ml-2">Hecho con ❤️ para estudiantes</span>
                        </p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="fixed inset-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm z-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600 dark:text-gray-400 font-medium">Cargando...</p>
                </div>
            </div>
        </div>

        <!-- Toast Notifications Container -->
        <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

        <!-- Global JavaScript -->
        <script>
            // Enhanced notification system
            window.showNotification = function(message, type = 'success', duration = 4000) {
                const container = document.getElementById('toastContainer');
                const id = 'toast-' + Date.now();
                
                const colors = {
                    success: 'bg-green-500 border-green-400',
                    error: 'bg-red-500 border-red-400',
                    warning: 'bg-yellow-500 border-yellow-400',
                    info: 'bg-blue-500 border-blue-400'
                };
                
                const icons = {
                    success: '✅',
                    error: '❌',
                    warning: '⚠️',
                    info: 'ℹ️'
                };
                
                const toast = document.createElement('div');
                toast.id = id;
                toast.className = `notification hide ${colors[type]} text-white px-6 py-4 rounded-lg border-l-4 shadow-2xl`;
                toast.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-lg">${icons[type]}</span>
                            <p class="font-medium">${message}</p>
                        </div>
                        <button onclick="closeNotification('${id}')" class="text-white/80 hover:text-white ml-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
                
                container.appendChild(toast);
                
                // Show animation
                setTimeout(() => {
                    toast.classList.remove('hide');
                    toast.classList.add('show');
                }, 100);
                
                // Auto hide
                setTimeout(() => {
                    closeNotification(id);
                }, duration);
            };
            
            window.closeNotification = function(id) {
                const toast = document.getElementById(id);
                if (toast) {
                    toast.classList.remove('show');
                    toast.classList.add('hide');
                    setTimeout(() => toast.remove(), 300);
                }
            };

            // Loading overlay functions
            window.showLoading = function() {
                document.getElementById('loadingOverlay').classList.remove('hidden');
            };
            
            window.hideLoading = function() {
                document.getElementById('loadingOverlay').classList.add('hidden');
            };

            // Enhanced form submissions
            document.addEventListener('DOMContentLoaded', function() {
                // Add loading states to forms
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function() {
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = submitBtn.innerHTML.replace(/^/, '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white inline-block mr-2"></div>');
                        }
                    });
                });

                // Smooth scroll for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });
            });
        </script>
    </body>
</html>
