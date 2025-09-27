<x-app-layout>
    <x-slot name="title">Crear Anuncio</x-slot>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                <span class="text-white text-2xl">🚀</span>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                    {{ __('Crear Nuevo Anuncio') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Comparte tu producto con la comunidad universitaria
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden shadow-2xl sm:rounded-2xl">
                <div class="p-8">
                    <!-- Progress Indicator -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-blue-600 dark:text-blue-400 font-medium">Información del Producto</span>
                            <span class="text-gray-500 dark:text-gray-400">Completa todos los campos</span>
                        </div>
                        <div class="mt-2 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-2 rounded-full w-full transition-all duration-300"></div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data" class="space-y-8" id="createListingForm">
                        @csrf

                        <!-- Información Básica Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3 pb-4 border-b border-gray-200 dark:border-gray-600">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-400 text-lg">📝</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Información Básica</h3>
                            </div>

                            <!-- Título -->
                            <div class="space-y-2">
                                <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <span>Título del anuncio</span>
                                        <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="title" 
                                           id="title"
                                           value="{{ old('title') }}"
                                           required
                                           maxlength="255"
                                           placeholder="Ej: iPhone 13 Pro Max 128GB - Como nuevo"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white placeholder-gray-400 transition-all duration-200 hover:border-blue-300">
                                    <div class="absolute right-3 top-3 text-sm text-gray-400">
                                        <span id="title-counter">0</span>/255
                                    </div>
                                </div>
                                @error('title')
                                    <p class="text-sm text-red-600 flex items-center space-x-1">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>

                            <!-- Categoría -->
                            <div class="space-y-2">
                                <label for="category_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <span>Categoría</span>
                                        <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <select name="category_id" 
                                            id="category_id"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white transition-all duration-200 hover:border-blue-300 appearance-none">
                                        <option value="">🏷️ Selecciona una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->icon }} {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('category_id')
                                    <p class="text-sm text-red-600 flex items-center space-x-1">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3 pb-4 border-b border-gray-200 dark:border-gray-600">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-purple-600 dark:text-purple-400 text-lg">📝</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Descripción Detallada</h3>
                            </div>

                            <div class="space-y-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <span>Descripción del producto</span>
                                        <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <textarea name="description" 
                                              id="description"
                                              rows="6"
                                              required
                                              maxlength="2000"
                                              placeholder="Describe tu producto de manera detallada:&#10;• Estado actual del producto&#10;• Características principales&#10;• Accesorios incluidos&#10;• Razón de venta&#10;• Cualquier detalle importante que el comprador deba conocer"
                                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-900 dark:text-white placeholder-gray-400 transition-all duration-200 hover:border-purple-300 resize-none">{{ old('description') }}</textarea>
                                    <div class="absolute bottom-3 right-3 text-sm text-gray-400">
                                        <span id="description-counter">{{ old('description') ? strlen(old('description')) : 0 }}</span>/2000
                                    </div>
                                </div>
                                @error('description')
                                    <p class="text-sm text-red-600 flex items-center space-x-1">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        <span class="font-medium">💡 Consejo:</span> Una descripción detallada ayuda a generar más confianza y atraer compradores serios.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Precio y Estado Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3 pb-4 border-b border-gray-200 dark:border-gray-600">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-green-600 dark:text-green-400 text-lg">💰</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Precio y Condición</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Precio -->
                                <div class="space-y-2">
                                    <label for="price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        <span class="flex items-center space-x-2">
                                            <span>Precio (CLP)</span>
                                            <span class="text-red-500">*</span>
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-lg font-semibold">$</span>
                                        </div>
                                        <input type="number" 
                                               name="price" 
                                               id="price"
                                               value="{{ old('price') }}"
                                               required
                                               min="0"
                                               max="999999"
                                               step="100"
                                               placeholder="50000"
                                               class="w-full pl-8 pr-16 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-900 dark:text-white placeholder-gray-400 transition-all duration-200 hover:border-green-300">
                                        <div class="absolute right-3 top-3 text-sm text-gray-400 font-medium">
                                            CLP
                                        </div>
                                    </div>
                                    @error('price')
                                        <p class="text-sm text-red-600 flex items-center space-x-1">
                                            <span>⚠️</span>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <!-- Estado -->
                                <div class="space-y-2">
                                    <label for="condition" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        <span class="flex items-center space-x-2">
                                            <span>Estado del producto</span>
                                            <span class="text-red-500">*</span>
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <select name="condition" 
                                                id="condition"
                                                required
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-900 dark:text-white transition-all duration-200 hover:border-green-300 appearance-none">
                                            <option value="">🏷️ Selecciona el estado</option>
                                            <option value="nuevo" {{ old('condition') == 'nuevo' ? 'selected' : '' }}>✨ Nuevo - Sin usar</option>
                                            <option value="como_nuevo" {{ old('condition') == 'como_nuevo' ? 'selected' : '' }}>🌟 Como nuevo - Excelente</option>
                                            <option value="bueno" {{ old('condition') == 'bueno' ? 'selected' : '' }}>👍 Buen estado - Funcional</option>
                                            <option value="aceptable" {{ old('condition') == 'aceptable' ? 'selected' : '' }}>⚠️ Estado aceptable - Uso normal</option>
                                            <option value="malo" {{ old('condition') == 'malo' ? 'selected' : '' }}>🔧 Necesita reparación</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('condition')
                                        <p class="text-sm text-red-600 flex items-center space-x-1">
                                            <span>⚠️</span>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación y Opciones Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3 pb-4 border-b border-gray-200 dark:border-gray-600">
                                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-orange-600 dark:text-orange-400 text-lg">📍</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ubicación y Opciones</h3>
                            </div>

                            <!-- Ubicación -->
                            <div class="space-y-2">
                                <label for="location" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center space-x-2">
                                        <span>Ubicación</span>
                                        <span class="text-gray-400 text-xs">(opcional)</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="location" 
                                           id="location"
                                           value="{{ old('location') }}"
                                           maxlength="255"
                                           placeholder="Ej: Campus Concepción, Los Ángeles, Centro de Concepción"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white placeholder-gray-400 transition-all duration-200 hover:border-orange-300">
                                </div>
                                @error('location')
                                    <p class="text-sm text-red-600 flex items-center space-x-1">
                                        <span>⚠️</span>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>

                            <!-- Opciones adicionales -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Opciones de venta</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-750 transition-colors">
                                        <input type="checkbox" 
                                               name="is_negotiable" 
                                               id="is_negotiable"
                                               value="1"
                                               {{ old('is_negotiable') ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-orange-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700">
                                        <label for="is_negotiable" class="flex-1 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                            <span class="flex items-center space-x-2">
                                                <span>💬</span>
                                                <span>Precio negociable</span>
                                            </span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Permite que los compradores propongan ofertas
                                            </p>
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-750 transition-colors">
                                        <input type="checkbox" 
                                               name="allows_exchange" 
                                               id="allows_exchange"
                                               value="1"
                                               {{ old('allows_exchange') ? 'checked' : '' }}
                                               class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-orange-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700">
                                        <label for="allows_exchange" class="flex-1 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                            <span class="flex items-center space-x-2">
                                                <span>🔄</span>
                                                <span>Acepto intercambios</span>
                                            </span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Está dispuesto/a a intercambiar por otros productos
                                            </p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Imágenes Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3 pb-4 border-b border-gray-200 dark:border-gray-600">
                                <div class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-pink-600 dark:text-pink-400 text-lg">📸</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Imágenes del Producto</h3>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label for="images" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        <span class="flex items-center space-x-2">
                                            <span>Subir imágenes</span>
                                            <span class="text-gray-400 text-xs">(máximo 5)</span>
                                        </span>
                                    </label>
                                    
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-pink-500 dark:hover:border-pink-400 transition-colors duration-200" id="dropzone">
                                        <input type="file" 
                                               name="images[]" 
                                               id="images"
                                               multiple
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                               class="hidden">
                                        
                                        <div class="space-y-3">
                                            <div class="mx-auto w-16 h-16 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            
                                            <div>
                                                <button type="button" onclick="document.getElementById('images').click()" 
                                                        class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Seleccionar imágenes
                                                </button>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                                    o arrastra y suelta las imágenes aquí
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                        <div class="flex items-start space-x-2">
                                            <span class="text-blue-600 dark:text-blue-400 text-sm">ℹ️</span>
                                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                                <p class="font-medium mb-1">Consejos para mejores fotos:</p>
                                                <ul class="text-xs space-y-1">
                                                    <li>• Usa buena iluminación natural</li>
                                                    <li>• Toma fotos desde diferentes ángulos</li>
                                                    <li>• Incluye detalles importantes o defectos</li>
                                                    <li>• Formatos: JPG, PNG, GIF, WebP (máx. 2MB c/u)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @error('images.*')
                                        <p class="text-sm text-red-600 flex items-center space-x-1">
                                            <span>⚠️</span>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>
                                
                                <!-- Preview de imágenes -->
                                <div id="image-preview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4"></div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex flex-col sm:flex-row items-center justify-between pt-8 space-y-4 sm:space-y-0">
                            <a href="{{ route('home') }}" 
                               class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancelar
                            </a>
                            
                            <div class="flex items-center space-x-4">
                                <button type="button" 
                                        onclick="document.getElementById('listing-form').reset(); location.reload()"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Limpiar
                                </button>
                                
                                <button type="submit" 
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Publicar Anuncio
                                </button>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contador de caracteres para título
            const titleInput = document.getElementById('title');
            const titleCounter = document.getElementById('title-counter');
            
            titleInput.addEventListener('input', function() {
                const currentLength = this.value.length;
                titleCounter.textContent = currentLength;
                
                if (currentLength > 240) {
                    titleCounter.parentElement.classList.add('text-red-500');
                } else {
                    titleCounter.parentElement.classList.remove('text-red-500');
                }
            });

            // Contador de caracteres para descripción
            const descriptionTextarea = document.getElementById('description');
            const descriptionCounter = document.getElementById('description-counter');
            
            descriptionTextarea.addEventListener('input', function() {
                const currentLength = this.value.length;
                descriptionCounter.textContent = currentLength;
                
                if (currentLength > 1900) {
                    descriptionCounter.parentElement.classList.add('text-red-500');
                } else {
                    descriptionCounter.parentElement.classList.remove('text-red-500');
                }
            });

            // Drag and drop para imágenes
            const dropzone = document.getElementById('dropzone');
            const imageInput = document.getElementById('images');
            const imagePreview = document.getElementById('image-preview');
            let selectedFiles = [];

            // Eventos drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropzone.classList.add('border-pink-500', 'bg-pink-50', 'dark:bg-pink-900/20');
            }

            function unhighlight(e) {
                dropzone.classList.remove('border-pink-500', 'bg-pink-50', 'dark:bg-pink-900/20');
            }

            dropzone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            }

            // Manejar selección de archivos
            imageInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            function handleFiles(files) {
                selectedFiles = Array.from(files);
                
                if (selectedFiles.length > 5) {
                    showNotification('Máximo 5 imágenes permitidas', 'error');
                    selectedFiles = selectedFiles.slice(0, 5);
                }
                
                updateImagePreview();
            }

            function updateImagePreview() {
                imagePreview.innerHTML = '';
                
                selectedFiles.forEach((file, index) => {
                    if (file.size > 2048000) { // 2MB
                        showNotification(`La imagen ${file.name} es muy grande. Máximo 2MB.`, 'error');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" 
                                 alt="Preview ${index + 1}" 
                                 class="w-full h-32 object-cover rounded-xl border-2 border-gray-200 dark:border-gray-600 group-hover:border-pink-500 transition-all duration-200">
                            <div class="absolute top-2 left-2 bg-pink-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                ${index + 1}
                            </div>
                            <button type="button" 
                                    onclick="removeImage(${index})"
                                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                ×
                            </button>
                            <div class="absolute bottom-2 left-2 right-2 bg-black/50 text-white text-xs p-1 rounded truncate">
                                ${file.name}
                            </div>
                        `;
                        imagePreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Función global para remover imagen
            window.removeImage = function(index) {
                selectedFiles.splice(index, 1);
                updateFileInput();
                updateImagePreview();
                showNotification('Imagen eliminada', 'success');
            };

            function updateFileInput() {
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                imageInput.files = dt.files;
            }

            // Sistema de notificaciones
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ${
                    type === 'error' ? 'bg-red-500 text-white' : 
                    type === 'success' ? 'bg-green-500 text-white' : 
                    'bg-blue-500 text-white'
                }`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);
                
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }

            // Validación del formulario
            document.getElementById('listing-form').addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const description = document.getElementById('description').value.trim();
                const price = document.getElementById('price').value;
                const category = document.getElementById('category_id').value;
                const condition = document.getElementById('condition').value;

                if (!title || !description || !price || !category || !condition) {
                    e.preventDefault();
                    showNotification('Por favor completa todos los campos obligatorios', 'error');
                    return;
                }

                if (title.length < 10) {
                    e.preventDefault();
                    showNotification('El título debe tener al menos 10 caracteres', 'error');
                    return;
                }

                if (description.length < 20) {
                    e.preventDefault();
                    showNotification('La descripción debe tener al menos 20 caracteres', 'error');
                    return;
                }

                // Mostrar loading
                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = `
                    <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Publicando...
                `;
                submitBtn.disabled = true;
            });
        });
    </script>
</x-app-layout>