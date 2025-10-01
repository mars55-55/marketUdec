<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Listing;

class SearchListingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * BDD: Como estudiante universitario
     * Quiero buscar y filtrar anuncios
     * Para encontrar productos que necesito
     */

    /** @test */
    public function a_user_can_search_listings_by_title()
    {
        // Given - Contexto: Varios anuncios en la plataforma
        $category = Category::factory()->create();
        
        $calculusBook = Listing::factory()->create([
            'title' => 'Libro de Cálculo Stewart',
            'description' => 'Matemáticas universitarias',
            'category_id' => $category->id,
            'status' => 'active'
        ]);
        
        $physicsBook = Listing::factory()->create([
            'title' => 'Libro de Física Serway',
            'description' => 'Ciencias básicas',
            'category_id' => $category->id,
            'status' => 'active'
        ]);

        // When - Acción: Busca por término específico
        $response = $this->get(route('search', ['q' => 'Cálculo']));

        // Then - Resultado: Solo encuentra el libro de cálculo
        $response->assertStatus(200);
        $response->assertSeeText('Libro de Cálculo Stewart');
        $response->assertDontSeeText('Libro de Física Serway');
    }

    /** @test */
    public function a_user_can_search_listings_by_description()
    {
        // Given
        $category = Category::factory()->create();
        
        Listing::factory()->create([
            'title' => 'Producto A',
            'description' => 'Para estudiantes de ingeniería en sistemas',
            'category_id' => $category->id,
            'status' => 'active'
        ]);
        
        Listing::factory()->create([
            'title' => 'Producto B',
            'description' => 'Para estudiantes de medicina',
            'category_id' => $category->id,
            'status' => 'active'
        ]);

        // When - Busca por contenido en descripción
        $response = $this->get(route('search', ['q' => 'sistemas']));

        // Then
        $response->assertStatus(200);
        $response->assertSeeText('Producto A');
        $response->assertDontSeeText('Producto B');
    }

    /** @test */
    public function a_user_can_filter_listings_by_category()
    {
        // Given - Diferentes categorías con anuncios
        $booksCategory = Category::factory()->create(['name' => 'Libros']);
        $electronicsCategory = Category::factory()->create(['name' => 'Electrónicos']);
        
        $book = Listing::factory()->create([
            'title' => 'Libro de Matemáticas',
            'category_id' => $booksCategory->id,
            'status' => 'active'
        ]);
        
        $laptop = Listing::factory()->create([
            'title' => 'Laptop Dell',
            'category_id' => $electronicsCategory->id,
            'status' => 'active'
        ]);

        // When - Filtra por categoría específica
        $response = $this->get(route('search', ['category' => $booksCategory->id]));

        // Then - Solo muestra anuncios de esa categoría
        $response->assertStatus(200);
        $response->assertSeeText('Libro de Matemáticas');
        $response->assertDontSeeText('Laptop Dell');
    }

    /** @test */
    public function a_user_can_filter_listings_by_price_range()
    {
        // Given - Anuncios con diferentes precios
        $category = Category::factory()->create();
        
        $cheapItem = Listing::factory()->create([
            'title' => 'Producto Barato',
            'price' => 5000,
            'category_id' => $category->id,
            'status' => 'active'
        ]);
        
        $midPriceItem = Listing::factory()->create([
            'title' => 'Producto Medio',
            'price' => 25000,
            'category_id' => $category->id,
            'status' => 'active'
        ]);
        
        $expensiveItem = Listing::factory()->create([
            'title' => 'Producto Caro',
            'price' => 80000,
            'category_id' => $category->id,
            'status' => 'active'
        ]);

        // When - Filtra por rango de precio
        $response = $this->get(route('search', [
            'min_price' => 10000,
            'max_price' => 50000
        ]));

        // Then - Solo muestra productos en ese rango
        $response->assertStatus(200);
        $response->assertSeeText('Producto Medio');
        $response->assertDontSeeText('Producto Barato');
        $response->assertDontSeeText('Producto Caro');
    }

    /** @test */
    public function it_combines_multiple_filters_correctly()
    {
        // Given - Setup complejo con múltiples variables
        $booksCategory = Category::factory()->create(['name' => 'Libros']);
        $electronicsCategory = Category::factory()->create(['name' => 'Electrónicos']);
        
        // Libro que cumple todos los criterios
        $targetBook = Listing::factory()->create([
            'title' => 'Cálculo Diferencial',
            'description' => 'Libro universitario excelente',
            'price' => 30000,
            'category_id' => $booksCategory->id,
            'status' => 'active'
        ]);
        
        // Libro fuera del rango de precio
        Listing::factory()->create([
            'title' => 'Cálculo Integral',
            'price' => 80000,
            'category_id' => $booksCategory->id,
            'status' => 'active'
        ]);
        
        // Electrónico con precio correcto pero categoría diferente
        Listing::factory()->create([
            'title' => 'Calculadora',
            'price' => 25000,
            'category_id' => $electronicsCategory->id,
            'status' => 'active'
        ]);

        // When - Aplica múltiples filtros
        $response = $this->get(route('search', [
            'q' => 'Cálculo',
            'category' => $booksCategory->id,
            'min_price' => 20000,
            'max_price' => 50000
        ]));

        // Then - Solo encuentra el que cumple todos los criterios
        $response->assertStatus(200);
        $response->assertSeeText('Cálculo Diferencial');
        $response->assertDontSeeText('Cálculo Integral');
        $response->assertDontSeeText('Calculadora');
    }

    /** @test */
    public function it_only_shows_active_and_sold_listings_in_search()
    {
        // Given - Anuncios en diferentes estados
        $category = Category::factory()->create();
        
        $activeListing = Listing::factory()->create([
            'title' => 'Anuncio Activo',
            'category_id' => $category->id,
            'status' => 'active'
        ]);
        
        $soldListing = Listing::factory()->create([
            'title' => 'Anuncio Vendido',
            'category_id' => $category->id,
            'status' => 'sold'
        ]);
        
        $pausedListing = Listing::factory()->create([
            'title' => 'Anuncio Pausado',
            'category_id' => $category->id,
            'status' => 'paused'
        ]);
        
        $blockedListing = Listing::factory()->create([
            'title' => 'Anuncio Bloqueado',
            'category_id' => $category->id,
            'status' => 'blocked'
        ]);

        // When - Realiza búsqueda general
        $response = $this->get(route('search'));

        // Then - Solo muestra activos y vendidos
        $response->assertStatus(200);
        $response->assertSeeText('Anuncio Activo');
        $response->assertSeeText('Anuncio Vendido');
        $response->assertDontSeeText('Anuncio Pausado');
        $response->assertDontSeeText('Anuncio Bloqueado');
    }

    /** @test */
    public function it_prioritizes_active_listings_over_sold_ones()
    {
        // Given - Anuncios activos y vendidos
        $category = Category::factory()->create();
        
        // Crear anuncio vendido primero (más antiguo)
        $soldListing = Listing::factory()->create([
            'title' => 'Producto Vendido',
            'category_id' => $category->id,
            'status' => 'sold',
            'created_at' => now()->subDays(2)
        ]);
        
        // Crear anuncio activo después (más reciente)
        $activeListing = Listing::factory()->create([
            'title' => 'Producto Activo',
            'category_id' => $category->id,
            'status' => 'active',
            'created_at' => now()->subDay()
        ]);

        // When
        $response = $this->get(route('search'));

        // Then - Activo debe aparecer antes que vendido
        $response->assertStatus(200);
        $content = $response->getContent();
        $activePosition = strpos($content, 'Producto Activo');
        $soldPosition = strpos($content, 'Producto Vendido');
        
        $this->assertLessThan($soldPosition, $activePosition);
    }

    /** @test */
    public function it_handles_empty_search_results_gracefully()
    {
        // Given - No hay anuncios que coincidan
        $category = Category::factory()->create();
        
        Listing::factory()->create([
            'title' => 'Producto Existente',
            'category_id' => $category->id,
            'status' => 'active'
        ]);

        // When - Busca algo que no existe
        $response = $this->get(route('search', ['q' => 'ProductoInexistente']));

        // Then - Maneja resultados vacíos apropiadamente
        $response->assertStatus(200);
        $response->assertDontSeeText('Producto Existente');
        // Debería mostrar mensaje de "no se encontraron resultados"
    }

    /** @test */
    public function it_provides_pagination_for_search_results()
    {
        // Given - Muchos anuncios que coinciden con búsqueda
        $category = Category::factory()->create();
        
        // Crear 25 anuncios (más que el límite de paginación típico)
        Listing::factory()->count(25)->create([
            'title' => 'Libro Universitario',
            'category_id' => $category->id,
            'status' => 'active'
        ]);

        // When - Realiza búsqueda
        $response = $this->get(route('search', ['q' => 'Libro']));

        // Then - Debe incluir controles de paginación
        $response->assertStatus(200);
        $response->assertSeeText('Libro Universitario');
        // La respuesta debe contener elementos de paginación
    }

    /** @test */
    public function it_maintains_search_parameters_in_pagination_links()
    {
        // Given - Setup con parámetros de búsqueda
        $category = Category::factory()->create();
        
        Listing::factory()->count(25)->create([
            'title' => 'Producto Filtrado',
            'category_id' => $category->id,
            'status' => 'active',
            'price' => 25000
        ]);

        // When - Búsqueda con múltiples parámetros
        $response = $this->get(route('search', [
            'q' => 'Producto',
            'category' => $category->id,
            'min_price' => 20000,
            'max_price' => 30000
        ]));

        // Then - Los enlaces de paginación mantienen los parámetros
        $response->assertStatus(200);
        $content = $response->getContent();
        
        // Verificar que los parámetros se mantienen en la URL
        $this->assertStringContainsString('q=Producto', $content);
        $this->assertStringContainsString("category={$category->id}", $content);
        $this->assertStringContainsString('min_price=20000', $content);
    }
}