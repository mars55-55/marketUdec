<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Listing;

class CreateListingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * BDD: Como estudiante universitario
     * Quiero publicar un anuncio de venta
     * Para poder vender mis libros y artículos
     */

    /** @test */
    public function a_student_can_create_a_listing_when_authenticated()
    {
        // Given - Contexto: Un estudiante autenticado
        $user = User::factory()->create([
            'name' => 'María Estudiante',
            'email' => 'maria@udec.cl',
            'career' => 'Ingeniería Civil',
            'campus' => 'Campus Concepción'
        ]);
        
        $category = Category::factory()->create([
            'name' => 'Libros de Texto',
            'is_active' => true
        ]);

        $this->actingAs($user);

        // When - Acción: Intenta crear un anuncio
        $listingData = [
            'category_id' => $category->id,
            'title' => 'Libro de Cálculo Stewart - 8va Edición',
            'description' => 'Libro en excelente estado, sin rayones. Incluye solucionario.',
            'price' => 35000,
            'condition' => 'como_nuevo',
            'location' => 'Campus Concepción',
            'is_negotiable' => true,
            'allows_exchange' => false
        ];

        $response = $this->post(route('listings.store'), $listingData);

        // Then - Resultado esperado: El anuncio se crea exitosamente
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('listings', [
            'user_id' => $user->id,
            'title' => 'Libro de Cálculo Stewart - 8va Edición',
            'price' => 35000,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function a_guest_cannot_create_listings()
    {
        // Given - Contexto: Usuario no autenticado
        $category = Category::factory()->create();

        // When - Acción: Intenta crear un anuncio sin estar logueado
        $response = $this->post(route('listings.store'), [
            'category_id' => $category->id,
            'title' => 'Título del anuncio',
            'description' => 'Descripción',
            'price' => 10000,
            'condition' => 'bueno'
        ]);

        // Then - Resultado: Es redirigido al login
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('listings', [
            'title' => 'Título del anuncio'
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_listing()
    {
        // Given - Contexto: Usuario autenticado
        $user = User::factory()->create();
        $this->actingAs($user);

        // When - Acción: Envía formulario con campos vacíos
        $response = $this->post(route('listings.store'), []);

        // Then - Resultado: Se muestran errores de validación
        $response->assertSessionHasErrors(['title', 'description', 'price', 'category_id']);
        $this->assertEquals(0, Listing::count());
    }

    /** @test */
    public function it_validates_price_is_numeric_and_positive()
    {
        // Given
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user);

        // When - Precio inválido
        $response = $this->post(route('listings.store'), [
            'category_id' => $category->id,
            'title' => 'Título válido',
            'description' => 'Descripción válida',
            'price' => -1000, // Precio negativo
            'condition' => 'bueno'
        ]);

        // Then
        $response->assertSessionHasErrors(['price']);
    }

    /** @test */
    public function it_sets_default_values_for_optional_fields()
    {
        // Given
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user);

        // When - Solo campos requeridos
        $response = $this->post(route('listings.store'), [
            'category_id' => $category->id,
            'title' => 'Anuncio Mínimo',
            'description' => 'Solo lo esencial',
            'price' => 15000,
            'condition' => 'bueno'
        ]);

        // Then - Se asignan valores por defecto
        $listing = Listing::where('title', 'Anuncio Mínimo')->first();
        $this->assertNotNull($listing);
        $this->assertEquals('active', $listing->status);
        $this->assertFalse($listing->is_negotiable);
        $this->assertFalse($listing->allows_exchange);
    }

    /** @test */
    public function it_can_create_listing_with_all_optional_fields()
    {
        // Given
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user);

        // When - Todos los campos opcionales incluidos
        $completeData = [
            'category_id' => $category->id,
            'title' => 'Anuncio Completo',
            'description' => 'Con todos los campos',
            'price' => 50000,
            'condition' => 'nuevo',
            'location' => 'Campus Central',
            'is_negotiable' => true,
            'allows_exchange' => true
        ];

        $response = $this->post(route('listings.store'), $completeData);

        // Then - Todos los campos se guardan correctamente
        $this->assertDatabaseHas('listings', [
            'title' => 'Anuncio Completo',
            'location' => 'Campus Central',
            'is_negotiable' => true,
            'allows_exchange' => true
        ]);
    }

    /** @test */
    public function it_redirects_to_listing_show_page_after_creation()
    {
        // Given
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user);

        // When
        $response = $this->post(route('listings.store'), [
            'category_id' => $category->id,
            'title' => 'Nuevo Anuncio',
            'description' => 'Descripción del anuncio',
            'price' => 25000,
            'condition' => 'bueno'
        ]);

        // Then
        $listing = Listing::where('title', 'Nuevo Anuncio')->first();
        $response->assertRedirect(route('listings.show', $listing));
    }

    /** @test */
    public function it_shows_success_message_after_creating_listing()
    {
        // Given
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user);

        // When
        $response = $this->post(route('listings.store'), [
            'category_id' => $category->id,
            'title' => 'Anuncio con Mensaje',
            'description' => 'Para verificar mensaje de éxito',
            'price' => 30000,
            'condition' => 'como_nuevo'
        ]);

        // Then
        $response->assertSessionHas('success');
        $this->assertEquals(
            'Anuncio creado exitosamente',
            session('success')
        );
    }

    /** @test */
    public function it_only_allows_valid_condition_values()
    {
        // Given
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this->actingAs($user);

        $validConditions = ['nuevo', 'como_nuevo', 'bueno', 'aceptable', 'para_repuestos'];

        foreach ($validConditions as $condition) {
            // When
            $response = $this->post(route('listings.store'), [
                'category_id' => $category->id,
                'title' => "Anuncio {$condition}",
                'description' => "Condición: {$condition}",
                'price' => 20000,
                'condition' => $condition
            ]);

            // Then
            $response->assertSessionHasNoErrors();
        }

        // When - Condición inválida
        $response = $this->post(route('listings.store'), [
            'category_id' => $category->id,
            'title' => 'Anuncio Inválido',
            'description' => 'Con condición inválida',
            'price' => 20000,
            'condition' => 'condicion_invalida'
        ]);

        // Then
        $response->assertSessionHasErrors(['condition']);
    }
}