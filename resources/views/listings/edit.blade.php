<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Anuncio') }}
            </h2>
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Editando anuncio</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Progreso del formulario -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <span class="text-blue-600 dark:text-blue-400 font-medium">Información del Producto</span>
                            <span class="text-gray-500">Completa todos los campos</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300" style="width: 100%"></div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('listings.update', $listing) }}" enctype="multipart/form-data" class="space-y-8" id="listing-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- Información Básica Section -->
                        <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="flex items-center mb-6">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full mr-3">
                                    <span class="text-blue-600 dark:text-blue-400 font-bold text-sm">1</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Información Básica</h3>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Título -->
                                <div class="lg:col-span-2">
                                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            Título del anuncio
                                            <span class="text-red-500 ml-1">*</span>
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               id="title" 
                                               name="title"
                                               value="{{ old('title', $listing->title) }}"
                                               required 
                                               maxlength="255"
                                               placeholder="Ej: iPhone 13 Pro Max 256GB"
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-white placeholder-gray-400 transition-all duration-200 hover:border-blue-300">
                                        <div class="absolute right-3 top-3 text-xs text-gray-400" id="title-counter">0/255</div>
                                    </div>
                                    @error('title')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Categoría -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                            Categoría
                                            <span class="text-red-500 ml-1">*</span>
                                        </span>
                                    </label>
                                    <select name="category_id" 
                                            id="category_id"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-900 dark:text-white transition-all duration-200 hover:border-green-300">
                                        <option value="">🏷️ Selecciona una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $listing->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->icon }} {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Descripción Section -->
                        <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="flex items-center mb-6">
                                <div class="flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full mr-3">
                                    <span class="text-purple-600 dark:text-purple-400 font-bold text-sm">2</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Descripción Detallada</h3>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                        </svg>
                                        Descripción del producto
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <textarea name="description" 
                                              id="description"
                                              required
                                              rows="6"
                                              maxlength="2000"
                                              placeholder="Describe tu producto en detalle: estado, características, motivo de venta, etc."
                                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-900 dark:text-white placeholder-gray-400 transition-all duration-200 hover:border-purple-300 resize-none">{{ old('description', $listing->description) }}</textarea>
                                    <div class="absolute bottom-3 right-3 text-xs text-gray-400" id="description-counter">0/2000</div>
                                </div>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                
                                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        💡 <strong>Consejo:</strong> Una buena descripción aumenta las posibilidades de venta. Incluye detalles como estado, marca, modelo, etc.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Precio y Condición Section -->
                        <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="flex items-center mb-6">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full mr-3">
                                    <span class="text-green-600 dark:text-green-400 font-bold text-sm">3</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Precio y Condición</h3>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Precio -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            Precio
                                            <span class="text-red-500 ml-1">*</span>
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-lg font-semibold">$</span>
                                        </div>
                                        <input type="number" 
                                               name="price" 
                                               id="price"
                                               value="{{ old('price', $listing->price) }}"
                                               required
                                               min="0"
                                               max="99999999"
                                               step="100"
                                               placeholder="50000"
                                               class="w-full pl-8 pr-16 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-900 dark:text-white placeholder-gray-400 transition-all duration-200 hover:border-green-300">
                                        <div class="absolute right-3 top-3 text-sm text-gray-400 font-medium">
                                            CLP
                                        </div>
                                    </div>
                                    @error('price')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Condición -->
                                <div>
                                    <label for="condition" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Estado del producto
                                            <span class="text-red-500 ml-1">*</span>
                                        </span>
                                    </label>
                                    <select name="condition" 
                                            id="condition"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-900 dark:text-white transition-all duration-200 hover:border-orange-300">
                                        <option value="">🏷️ Selecciona el estado</option>
                                        <option value="nuevo" {{ old('condition', $listing->condition) == 'nuevo' ? 'selected' : '' }}>✨ Nuevo - Sin usar</option>
                                        <option value="como_nuevo" {{ old('condition', $listing->condition) == 'como_nuevo' ? 'selected' : '' }}>🌟 Como nuevo - Excelente</option>
                                        <option value="bueno" {{ old('condition', $listing->condition) == 'bueno' ? 'selected' : '' }}>👍 Buen estado - Funcional</option>
                                        <option value="aceptable" {{ old('condition', $listing->condition) == 'aceptable' ? 'selected' : '' }}>⚠️ Estado aceptable - Uso normal</option>
                                        <option value="malo" {{ old('condition', $listing->condition) == 'malo' ? 'selected' : '' }}>🔧 Necesita reparación</option>
                                    </select>
                                    @error('condition')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación y Opciones Section -->
                        <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="flex items-center mb-6">
                                <div class="flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full mr-3">
                                    <span class="text-red-600 dark:text-red-400 font-bold text-sm">4</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ubicación y Opciones</h3>
                            </div>

                            <!-- Ubicación -->
                            <div class="mb-6">
                                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Ubicación
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="location" 
                                           id="location"
                                           value="{{ old('location', $listing->location) }}"
                                           required
                                           placeholder="rosal - cundinamarca - Colombia"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-900 dark:text-white placeholder-gray-400 transition-all duration-200 hover:border-red-300">
                                </div>
                                @error('location')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Opciones adicionales -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Precio negociable -->
                                <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200 dark:border-green-800">
                                    <input type="checkbox" 
                                           name="is_negotiable" 
                                           id="is_negotiable"
                                           value="1"
                                           {{ old('is_negotiable', $listing->is_negotiable) ? 'checked' : '' }}
                                           class="w-5 h-5 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="is_negotiable" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        💰 <strong>Precio negociable</strong>
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Permite que los compradores propongan ofertas</div>
                                    </label>
                                </div>

                                <!-- Acepto intercambios -->
                                <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                    <input type="checkbox" 
                                           name="allows_exchange" 
                                           id="allows_exchange"
                                           value="1"
                                           {{ old('allows_exchange', $listing->allows_exchange) ? 'checked' : '' }}
                                           class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="allows_exchange" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        🔄 <strong>Acepto intercambios</strong>
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Estás dispuesto/a a intercambiar por otros productos</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Imágenes Section -->
                        <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="flex items-center mb-6">
                                <div class="flex items-center justify-center w-8 h-8 bg-pink-100 dark:bg-pink-900 rounded-full mr-3">
                                    <span class="text-pink-600 dark:text-pink-400 font-bold text-sm">5</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Imágenes del Producto</h3>
                            </div>

                            <!-- Imágenes actuales -->
                            @if($listing->images && $listing->images->count() > 0)
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Imágenes actuales</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($listing->images as $image)
                                            <div class="relative group">
                                                <img src="{{ Storage::url($image->image_path) }}" alt="Imagen del producto" class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-xs">Click para eliminar</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Upload de nuevas imágenes -->
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-pink-500 transition-colors duration-200">
                                <input type="file" 
                                       name="images[]" 
                                       id="images"
                                       multiple
                                       accept="image/*"
                                       class="hidden">
                                
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-pink-100 dark:bg-pink-900 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    
                                    <button type="button" 
                                            onclick="document.getElementById('images').click()"
                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Seleccionar Imágenes
                                    </button>
                                    
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">o arrastra y suelta las imágenes aquí</p>
                                </div>
                            </div>
                            
                            @error('images.*')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">Recomendaciones para las imágenes:</p>
                                        <ul class="list-disc list-inside space-y-1 text-xs">
                                            <li>• Formatos: JPG, PNG, GIF, WebP (máx. 2MB c/u)</li>
                                            <li>• Usa buena iluminación natural</li>
                                            <li>• Toma fotos desde diferentes ángulos</li>
                                            <li>• Muestra detalles importantes y defectos</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Todos los datos están seguros y encriptados
                                </span>
                            </div>
                            
                            <div class="flex space-x-4">
                                <button type="button" 
                                        onclick="window.location.href='{{ route('listings.show', $listing) }}'"
                                        class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancelar
                                </button>
                                
                                <button type="submit" 
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Actualizar Anuncio
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Contadores de caracteres
        const titleInput = document.getElementById('title');
        const titleCounter = document.getElementById('title-counter');
        const descriptionInput = document.getElementById('description');
        const descriptionCounter = document.getElementById('description-counter');

        function updateCounter(input, counter, max) {
            const count = input.value.length;
            counter.textContent = `${count}/${max}`;
            
            if (count > max * 0.9) {
                counter.classList.add('text-red-500');
                counter.classList.remove('text-gray-400');
            } else {
                counter.classList.remove('text-red-500');
                counter.classList.add('text-gray-400');
            }
        }

        titleInput.addEventListener('input', () => updateCounter(titleInput, titleCounter, 255));
        descriptionInput.addEventListener('input', () => updateCounter(descriptionInput, descriptionCounter, 2000));

        // Inicializar contadores
        updateCounter(titleInput, titleCounter, 255);
        updateCounter(descriptionInput, descriptionCounter, 2000);

        // Validación del formulario
        document.getElementById('listing-form').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const price = document.getElementById('price').value;
            const categoryId = document.getElementById('category_id').value;
            const condition = document.getElementById('condition').value;
            const location = document.getElementById('location').value.trim();

            if (!title || !description || !price || !categoryId || !condition || !location) {
                e.preventDefault();
                alert('Por favor, completa todos los campos obligatorios.');
                return;
            }

            if (title.length < 10) {
                e.preventDefault();
                alert('El título debe tener al menos 10 caracteres.');
                return;
            }

            if (description.length < 20) {
                e.preventDefault();
                alert('La descripción debe tener al menos 20 caracteres.');
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
                Actualizando...
            `;
            submitBtn.disabled = true;
        });
    </script>
</x-app-layout>