<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            🔒 {{ __('Configuración de Privacidad') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Controla qué información personal es visible para otros usuarios.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.privacy.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        @php
            $privacySettings = $user->privacy_settings ?? [];
        @endphp

        <div class="space-y-6">
            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">
                Información visible en tu perfil público:
            </h3>

            <!-- Mostrar email -->
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">📧</span>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">Email</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</div>
                    </div>
                </div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="show_email" 
                           value="1"
                           {{ ($privacySettings['show_email'] ?? false) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <!-- Mostrar teléfono -->
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">📱</span>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">Teléfono</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $user->phone ?: 'No configurado' }}
                        </div>
                    </div>
                </div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="show_phone" 
                           value="1"
                           {{ ($privacySettings['show_phone'] ?? false) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <!-- Mostrar sede -->
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">🏫</span>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">Sede</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $user->campus ?: 'No configurada' }}
                        </div>
                    </div>
                </div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="show_campus" 
                           value="1"
                           {{ ($privacySettings['show_campus'] ?? true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <!-- Mostrar carrera -->
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">🎓</span>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">Carrera</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $user->career ?: 'No configurada' }}
                        </div>
                    </div>
                </div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="show_career" 
                           value="1"
                           {{ ($privacySettings['show_career'] ?? true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <!-- Mostrar número de publicaciones -->
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">📦</span>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">Estadísticas de anuncios</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Mostrar número de publicaciones y reseñas
                        </div>
                    </div>
                </div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="show_listings_count" 
                           value="1"
                           {{ ($privacySettings['show_listings_count'] ?? true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">
                Interacciones:
            </h3>

            <!-- Permitir mensajes -->
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">💬</span>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">Permitir mensajes privados</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Otros usuarios pueden iniciarte conversaciones
                        </div>
                    </div>
                </div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="allow_messages" 
                           value="1"
                           {{ ($privacySettings['allow_messages'] ?? true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start space-x-2">
                <span class="text-blue-600 dark:text-blue-400 text-lg mt-0.5">ℹ️</span>
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Importante:</strong> Tu nombre siempre será visible a otros usuarios. 
                    Estas configuraciones solo controlan información adicional. 
                    Puedes cambiar estas configuraciones en cualquier momento.
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar configuración') }}</x-primary-button>

            @if (session('status') === 'privacy-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 dark:text-green-400">{{ __('Configuración de privacidad guardada exitosamente.') }}</p>
            @endif
        </div>
    </form>
</section>