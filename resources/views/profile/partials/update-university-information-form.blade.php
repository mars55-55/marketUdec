<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            🎓 {{ __('Información Universitaria') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Actualiza tu información académica para que otros estudiantes puedan conocerte mejor.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.university.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Carrera -->
        <div>
            <x-input-label for="career" :value="__('Carrera')" />
            <x-text-input id="career" 
                          name="career" 
                          type="text" 
                          class="mt-1 block w-full" 
                          :value="old('career', $user->career)" 
                          placeholder="Ej: Ingeniería en Sistemas" />
            <x-input-error class="mt-2" :messages="$errors->get('career')" />
        </div>

        <!-- Sede/Campus -->
        <div>
            <x-input-label for="campus" :value="__('Sede/Campus')" />
            <select id="campus" 
                    name="campus" 
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">Selecciona tu sede</option>
                <option value="Santiago" {{ old('campus', $user->campus) === 'Santiago' ? 'selected' : '' }}>Santiago</option>
                <option value="Concepción" {{ old('campus', $user->campus) === 'Concepción' ? 'selected' : '' }}>Concepción</option>
                <option value="Los Ángeles" {{ old('campus', $user->campus) === 'Los Ángeles' ? 'selected' : '' }}>Los Ángeles</option>
                <option value="Chillán" {{ old('campus', $user->campus) === 'Chillán' ? 'selected' : '' }}>Chillán</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('campus')" />
        </div>

        <!-- Biografía -->
        <div>
            <x-input-label for="bio" :value="__('Biografía')" />
            <textarea id="bio" 
                      name="bio" 
                      rows="4"
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                      placeholder="Cuéntanos algo sobre ti, tus intereses académicos, hobbies, etc.">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                <span id="bio-count">{{ strlen($user->bio ?? '') }}</span>/1000 caracteres
            </p>
        </div>

        <!-- Teléfono -->
        <div>
            <x-input-label for="phone" :value="__('Teléfono (opcional)')" />
            <x-text-input id="phone" 
                          name="phone" 
                          type="tel" 
                          class="mt-1 block w-full" 
                          :value="old('phone', $user->phone)" 
                          placeholder="Ej: +56 9 1234 5678" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Tu teléfono se mostrará según tus configuraciones de privacidad.
            </p>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar información') }}</x-primary-button>

            @if (session('status') === 'university-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 dark:text-green-400">{{ __('Información universitaria guardada exitosamente.') }}</p>
            @endif
        </div>
    </form>

    <script>
        // Contador de caracteres para biografía
        document.getElementById('bio').addEventListener('input', function(e) {
            const count = e.target.value.length;
            document.getElementById('bio-count').textContent = count;
            
            if (count > 1000) {
                e.target.style.borderColor = '#ef4444';
                document.getElementById('bio-count').style.color = '#ef4444';
            } else {
                e.target.style.borderColor = '';
                document.getElementById('bio-count').style.color = '';
            }
        });
    </script>
</section>