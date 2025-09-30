<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            📷 {{ __('Foto de Perfil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Actualiza tu foto de perfil para que otros usuarios puedan reconocerte fácilmente.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Vista previa de la foto actual -->
        <div class="flex items-center space-x-6">
            <div class="flex-shrink-0">
                @if($user->profile_photo)
                    <img id="photo-preview" src="{{ asset('storage/' . $user->profile_photo) }}" 
                         alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                @else
                    <div id="photo-preview" class="w-24 h-24 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center border-2 border-gray-300 dark:border-gray-600">
                        <span class="text-gray-600 dark:text-gray-300 text-xl font-semibold">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                @endif
            </div>

            <div class="space-y-2">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    {{ $user->profile_photo ? 'Foto actual' : 'Sin foto de perfil' }}
                </p>
                @if($user->profile_photo)
                    <form method="post" action="{{ route('profile.photo.delete') }}" class="inline">
                        @csrf
                        @method('delete')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')">
                            🗑️ Eliminar foto
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Formulario para subir nueva foto -->
        <form method="post" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="profile_photo" :value="__('Nueva foto de perfil')" />
                <input id="profile_photo" 
                       name="profile_photo" 
                       type="file" 
                       accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300"
                       onchange="previewImage(this)">
                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Actualizar foto') }}</x-primary-button>

                @if (session('status') === 'photo-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-green-600 dark:text-green-400">{{ __('Foto actualizada exitosamente.') }}</p>
                @endif

                @if (session('status') === 'photo-deleted')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-green-600 dark:text-green-400">{{ __('Foto eliminada exitosamente.') }}</p>
                @endif
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('photo-preview');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" 
                                               alt="Vista previa" 
                                               class="w-24 h-24 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">`;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</section>