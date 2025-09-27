<!-- Modal para dejar reseña -->
<div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ⭐ Dejar Reseña
                </h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Información del usuario -->
            <div id="reviewUserInfo" class="flex items-center space-x-3 mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <!-- Se llenará dinámicamente -->
            </div>

            <!-- Formulario -->
            <form id="reviewForm" method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" id="reviewUserId" name="user_id">
                <input type="hidden" id="reviewListingId" name="listing_id">

                <!-- Calificación -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Calificación
                    </label>
                    <div class="flex items-center space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    class="rating-star text-2xl text-gray-300 hover:text-yellow-400 transition-colors"
                                    data-rating="{{ $i }}"
                                    onclick="setRating({{ $i }})">
                                ⭐
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" id="ratingInput" name="rating" required>
                    <p id="ratingText" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Selecciona una calificación
                    </p>
                </div>

                <!-- Comentario -->
                <div class="mb-4">
                    <label for="reviewComment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Comentario (opcional)
                    </label>
                    <textarea id="reviewComment" 
                              name="comment" 
                              rows="4" 
                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                              placeholder="Comparte tu experiencia con este usuario..."
                              maxlength="1000"></textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span id="commentCount">0</span>/1000 caracteres
                    </p>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" 
                            onclick="closeReviewModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                        Publicar Reseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentRating = 0;
    const ratingTexts = {
        1: '⭐ Muy malo',
        2: '⭐⭐ Malo', 
        3: '⭐⭐⭐ Regular',
        4: '⭐⭐⭐⭐ Bueno',
        5: '⭐⭐⭐⭐⭐ Excelente'
    };

    function openReviewModal(userId, userName, userPhoto = null, listingId = null, listingTitle = null) {
        // Llenar información del usuario
        const userInfo = document.getElementById('reviewUserInfo');
        userInfo.innerHTML = `
            <div class="flex-shrink-0">
                ${userPhoto ? 
                    `<img src="${userPhoto}" alt="${userName}" class="w-10 h-10 rounded-full object-cover">` :
                    `<div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                        <span class="text-gray-600 dark:text-gray-300 font-semibold">${userName.charAt(0)}</span>
                    </div>`
                }
            </div>
            <div>
                <p class="font-medium text-gray-900 dark:text-gray-100">${userName}</p>
                ${listingTitle ? `<p class="text-sm text-gray-600 dark:text-gray-400">Sobre: ${listingTitle}</p>` : ''}
            </div>
        `;

        // Establecer valores ocultos
        document.getElementById('reviewUserId').value = userId;
        document.getElementById('reviewListingId').value = listingId || '';

        // Resetear formulario
        resetReviewForm();

        // Mostrar modal
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        resetReviewForm();
    }

    function resetReviewForm() {
        currentRating = 0;
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach(star => {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        });
        document.getElementById('ratingInput').value = '';
        document.getElementById('ratingText').textContent = 'Selecciona una calificación';
        document.getElementById('reviewComment').value = '';
        updateCommentCount();
    }

    function setRating(rating) {
        currentRating = rating;
        document.getElementById('ratingInput').value = rating;
        document.getElementById('ratingText').textContent = ratingTexts[rating];

        // Actualizar estrellas
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    function updateCommentCount() {
        const comment = document.getElementById('reviewComment').value;
        document.getElementById('commentCount').textContent = comment.length;
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Hover effect en estrellas
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach((star, index) => {
            star.addEventListener('mouseenter', function() {
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });

        // Restaurar estrellas al salir del hover
        document.getElementById('reviewModal').addEventListener('mouseleave', function() {
            setRating(currentRating);
        });

        // Contador de caracteres
        document.getElementById('reviewComment').addEventListener('input', updateCommentCount);

        // Cerrar modal al hacer clic fuera
        document.getElementById('reviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });
    });
</script>