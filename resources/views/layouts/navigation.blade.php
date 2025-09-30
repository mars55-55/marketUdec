<nav x-data="{ open: false }" class="glass dark:glass-dark border-b border-white/20 dark:border-gray-700/20 sticky top-0 z-50 backdrop-blur-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center group-hover:shadow-lg transition-all duration-200 group-hover:scale-105">
                            <span class="text-white font-bold text-lg">🎓</span>
                        </div>
                        <div class="hidden sm:block">
                            <div class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                MarketUdeC
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 -mt-1">
                                Marketplace Universitario
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home') || request()->routeIs('listings.*')" 
                                class="nav-link-enhanced flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-white/50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <span class="text-lg">🏠</span>
                        <span>{{ __('Inicio') }}</span>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('search')" :active="request()->routeIs('search')" 
                                class="nav-link-enhanced flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-white/50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <span class="text-lg">🔍</span>
                        <span>{{ __('Buscar') }}</span>
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                    class="nav-link-enhanced flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-white/50 dark:hover:bg-gray-700/50 transition-all duration-200">
                            <span class="text-lg">📊</span>
                            <span>{{ __('Dashboard') }}</span>
                        </x-nav-link>
                        
                        <x-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.*')" 
                                    class="nav-link-enhanced flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-white/50 dark:hover:bg-gray-700/50 transition-all duration-200 relative">
                            <span class="text-lg">❤️</span>
                            <span>{{ __('Favoritos') }}</span>
                            @if(auth()->user()->favorites()->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ auth()->user()->favorites()->count() }}
                                </span>
                            @endif
                        </x-nav-link>
                        
                        <x-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')" 
                                    class="nav-link-enhanced flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-white/50 dark:hover:bg-gray-700/50 transition-all duration-200 relative">
                            <span class="text-lg">💬</span>
                            <span>{{ __('Mensajes') }}</span>
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown / Auth Links -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" 
                        class="p-2 rounded-lg hover:bg-white/50 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                    <div x-show="!darkMode" class="text-gray-600 group-hover:text-yellow-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div x-show="darkMode" class="text-gray-400 group-hover:text-blue-400 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </div>
                </button>

                @auth
                    <!-- Quick Actions -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('listings.create') }}" 
                           class="btn-primary flex items-center space-x-2 text-sm">
                            <span class="text-lg">➕</span>
                            <span class="hidden lg:block">Publicar</span>
                        </a>

                        <!-- Notificaciones -->
                        <div class="relative" id="notificationsContainer">
                            <button onclick="toggleNotifications()" 
                                    class="relative p-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-white/50 dark:hover:bg-gray-700/50 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3-3V9a6 6 0 10-12 0v5l-3 3h13zM19 17v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0M9 21h6"></path>
                                </svg>
                                <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 items-center justify-center font-semibold hidden">0</span>
                            </button>
                        </div>
                    </div>

                    <x-dropdown align="right" width="64">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-3 px-4 py-2 rounded-xl hover:bg-white/50 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                         alt="{{ Auth::user()->name }}"
                                         class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600 group-hover:border-blue-400 transition-colors">
                                @else
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-sm group-hover:shadow-lg transition-all">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <div class="hidden md:block text-left">
                                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ Auth::user()->career ?? 'Usuario' }}
                                    </div>
                                </div>

                                <div class="text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- User Info Header -->
                            <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border-b border-gray-100 dark:border-gray-600">
                                <div class="flex items-center space-x-3">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                             alt="{{ Auth::user()->name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</div>
                                        @if(Auth::user()->rating > 0)
                                            <div class="flex items-center space-x-1 mt-1">
                                                <span class="text-yellow-400">⭐</span>
                                                <span class="text-xs text-gray-600 dark:text-gray-400">{{ number_format(Auth::user()->rating, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Links -->
                            <div class="py-2">
                                <x-dropdown-link :href="route('users.show', Auth::user())" class="flex items-center space-x-2 px-4 py-2 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                    <span class="text-lg">�</span>
                                    <span>{{ __('Mi Perfil Público') }}</span>
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('dashboard')" class="flex items-center space-x-2 px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/20">
                                    <span class="text-lg">📊</span>
                                    <span>{{ __('Dashboard') }}</span>
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('favorites.index')" class="flex items-center space-x-2 px-4 py-2 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <span class="text-lg">❤️</span>
                                    <span>{{ __('Mis Favoritos') }}</span>
                                    @if(auth()->user()->favorites()->count() > 0)
                                        <span class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                            {{ auth()->user()->favorites()->count() }}
                                        </span>
                                    @endif
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('conversations.index')" class="flex items-center space-x-2 px-4 py-2 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                    <span class="text-lg">💬</span>
                                    <span>{{ __('Mensajes') }}</span>
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('notifications.page')" class="flex items-center space-x-2 px-4 py-2 hover:bg-purple-50 dark:hover:bg-purple-900/20">
                                    <span class="text-lg">🔔</span>
                                    <span>{{ __('Notificaciones') }}</span>
                                    <span id="dropdownNotificationBadge" class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full hidden">0</span>
                                </x-dropdown-link>
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-600"></div>

                            <!-- Panel de Admin (solo para administradores) -->
                            @if(auth()->user()->is_admin)
                                <div class="py-2 bg-red-50 dark:bg-red-900/20">
                                    <x-dropdown-link :href="route('admin.index')" class="flex items-center space-x-2 px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-700 dark:text-red-300">
                                        <span class="text-lg">🛡️</span>
                                        <span class="font-medium">{{ __('Panel de Admin') }}</span>
                                    </x-dropdown-link>
                                </div>

                                <div class="border-t border-gray-100 dark:border-gray-600"></div>
                            @endif

                            <!-- Settings -->
                            <div class="py-2">
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <span class="text-lg">⚙️</span>
                                    <span>{{ __('Configuración') }}</span>
                                </x-dropdown-link>
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-600"></div>

                            <!-- Authentication -->
                            <div class="py-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="flex items-center space-x-2 px-4 py-2 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400">
                                        <span class="text-lg">🚪</span>
                                        <span>{{ __('Cerrar Sesión') }}</span>
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 text-sm font-medium">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Registrarse
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
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
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home') || request()->routeIs('listings.*')">
                🏠 {{ __('Inicio') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('search')" :active="request()->routeIs('search')">
                🔍 {{ __('Buscar') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    📊 {{ __('Dashboard') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.*')">
                    ❤️ {{ __('Favoritos') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')">
                    💬 {{ __('Mensajes') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        👤 {{ __('Mi Perfil') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('listings.create')">
                        ➕ {{ __('Crear Anuncio') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            🚪 {{ __('Cerrar Sesión') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <!-- Guest Links -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        🔑 {{ __('Iniciar Sesión') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('register')">
                        📝 {{ __('Registrarse') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>

@auth
<!-- Panel de Notificaciones -->
<div id="notificationsPanel" class="fixed top-16 right-4 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 hidden">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Notificaciones</h3>
            <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800">
                Marcar todo como leído
            </button>
        </div>
    </div>
    
    <div id="notificationsList" class="max-h-96 overflow-y-auto">
        <div class="p-4 text-center text-gray-500">
            <div class="text-3xl mb-2">🔔</div>
            <p>Cargando notificaciones...</p>
        </div>
    </div>
</div>

<script>
let notificationsPanel = null;
let notificationsList = null;
let notificationBadge = null;

document.addEventListener('DOMContentLoaded', function() {
    notificationsPanel = document.getElementById('notificationsPanel');
    notificationsList = document.getElementById('notificationsList');
    notificationBadge = document.getElementById('notificationBadge');
    
    // Cargar notificaciones al iniciar
    loadNotifications();
    
    // Actualizar cada 30 segundos
    setInterval(loadNotifications, 30000);
    
    // Cerrar panel al hacer click fuera
    document.addEventListener('click', function(e) {
        if (notificationsPanel && !notificationsPanel.contains(e.target) && 
            !document.getElementById('notificationsContainer').contains(e.target)) {
            notificationsPanel.classList.add('hidden');
        }
    });
});

function toggleNotifications() {
    if (notificationsPanel.classList.contains('hidden')) {
        notificationsPanel.classList.remove('hidden');
        loadNotifications();
    } else {
        notificationsPanel.classList.add('hidden');
    }
}

function loadNotifications() {
    fetch('{{ route("notifications.index") }}')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.unread_count);
            renderNotifications(data.notifications);
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
        });
}

function updateNotificationBadge(count) {
    const dropdownBadge = document.getElementById('dropdownNotificationBadge');
    
    if (count > 0) {
        const displayCount = count > 99 ? '99+' : count;
        
        notificationBadge.textContent = displayCount;
        notificationBadge.classList.remove('hidden');
        notificationBadge.classList.add('flex');
        
        if (dropdownBadge) {
            dropdownBadge.textContent = displayCount;
            dropdownBadge.classList.remove('hidden');
        }
    } else {
        notificationBadge.classList.add('hidden');
        notificationBadge.classList.remove('flex');
        
        if (dropdownBadge) {
            dropdownBadge.classList.add('hidden');
        }
    }
}

function renderNotifications(notifications) {
    if (notifications.length === 0) {
        notificationsList.innerHTML = `
            <div class="p-4 text-center text-gray-500">
                <div class="text-3xl mb-2">📭</div>
                <p>No tienes notificaciones</p>
            </div>
        `;
        return;
    }

    let html = '';
    notifications.forEach(notification => {
        const data = notification.data;
        const isRead = notification.read_at !== null;
        const timeAgo = formatTimeAgo(notification.created_at);
        
        html += `
            <div class="border-b border-gray-100 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer ${isRead ? 'opacity-75' : 'bg-blue-50 dark:bg-blue-900/10'}"
                 onclick="openNotification('${notification.id}', '${data.url}')">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <img src="${data.sender_avatar}" alt="${data.sender_name}" 
                             class="w-10 h-10 rounded-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            ${data.title}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                            ${data.message}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            ${timeAgo}
                        </p>
                    </div>
                    ${!isRead ? '<div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>' : ''}
                </div>
            </div>
        `;
    });
    
    notificationsList.innerHTML = html;
}

function openNotification(notificationId, url) {
    // Marcar como leída
    fetch(`{{ url('/notifications') }}/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(() => {
        // Actualizar conteo
        loadNotifications();
        // Redirigir
        window.location.href = url;
    });
}

function markAllAsRead() {
    fetch('{{ route("notifications.readAll") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(() => {
        loadNotifications();
    });
}

function formatTimeAgo(dateString) {
    const now = new Date();
    const date = new Date(dateString);
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Hace un momento';
    if (diffInSeconds < 3600) return `Hace ${Math.floor(diffInSeconds / 60)} min`;
    if (diffInSeconds < 86400) return `Hace ${Math.floor(diffInSeconds / 3600)} h`;
    if (diffInSeconds < 2592000) return `Hace ${Math.floor(diffInSeconds / 86400)} días`;
    
    return date.toLocaleDateString('es-ES');
}
</script>
@endauth
