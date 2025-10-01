<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_category_with_required_fields()
    {
        // Arrange - TDD: Definir estructura esperada
        $categoryData = [
            'name' => 'Libros de Texto',
            'slug' => 'libros-texto',
            'description' => 'Libros académicos y material de estudio',
            'icon' => '📚'
        ];

        // Act
        $category = Category::create($categoryData);

        // Assert
        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Libros de Texto', $category->name);
        $this->assertEquals('libros-texto', $category->slug);
        $this->assertDatabaseHas('categories', $categoryData);
    }

    /** @test */
    public function it_generates_slug_automatically_from_name()
    {
        // Arrange
        $category = new Category();
        $category->name = 'Electrónicos y Gadgets';

        // Act
        $slug = $category->generateSlug();

        // Assert
        $this->assertEquals('electronicos-y-gadgets', $slug);
    }

    /** @test */
    public function it_has_many_listings()
    {
        // Arrange
        $category = Category::factory()->create();
        
        // Act
        $listing1 = Listing::factory()->create(['category_id' => $category->id]);
        $listing2 = Listing::factory()->create(['category_id' => $category->id]);
        $listing3 = Listing::factory()->create(['category_id' => $category->id]);

        // Assert
        $this->assertCount(3, $category->listings);
        $this->assertTrue($category->listings->contains($listing1));
        $this->assertTrue($category->listings->contains($listing2));
        $this->assertTrue($category->listings->contains($listing3));
    }

    /** @test */
    public function it_counts_active_listings_only()
    {
        // Arrange
        $category = Category::factory()->create();
        
        $activeListing1 = Listing::factory()->create([
            'category_id' => $category->id,
            'status' => 'active'
        ]);
        
        $activeListing2 = Listing::factory()->create([
            'category_id' => $category->id,
            'status' => 'active'
        ]);
        
        $pausedListing = Listing::factory()->create([
            'category_id' => $category->id,
            'status' => 'paused'
        ]);
        
        $blockedListing = Listing::factory()->create([
            'category_id' => $category->id,
            'status' => 'blocked'
        ]);

        // Act
        $activeCount = $category->activeListingsCount();

        // Assert
        $this->assertEquals(2, $activeCount);
    }

    /** @test */
    public function it_knows_if_it_is_active()
    {
        // Arrange
        $activeCategory = Category::factory()->create(['is_active' => true]);
        $inactiveCategory = Category::factory()->create(['is_active' => false]);

        // Act & Assert
        $this->assertTrue($activeCategory->isActive());
        $this->assertFalse($inactiveCategory->isActive());
    }

    /** @test */
    public function it_scopes_only_active_categories()
    {
        // Arrange
        $activeCategory1 = Category::factory()->create(['is_active' => true]);
        $activeCategory2 = Category::factory()->create(['is_active' => true]);
        $inactiveCategory = Category::factory()->create(['is_active' => false]);

        // Act
        $activeCategories = Category::active()->get();

        // Assert
        $this->assertCount(2, $activeCategories);
        $this->assertTrue($activeCategories->contains($activeCategory1));
        $this->assertTrue($activeCategories->contains($activeCategory2));
        $this->assertFalse($activeCategories->contains($inactiveCategory));
    }

    /** @test */
    public function it_finds_category_by_slug()
    {
        // Arrange
        $category = Category::factory()->create(['slug' => 'electronicos']);

        // Act
        $found = Category::findBySlug('electronicos');

        // Assert
        $this->assertNotNull($found);
        $this->assertEquals($category->id, $found->id);
        $this->assertEquals('electronicos', $found->slug);
    }

    /** @test */
    public function it_returns_null_when_slug_not_found()
    {
        // Act
        $notFound = Category::findBySlug('categoria-inexistente');

        // Assert
        $this->assertNull($notFound);
    }

    /** @test */
    public function it_validates_unique_slug()
    {
        // Arrange
        Category::factory()->create(['slug' => 'libros-texto']);

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Category::create([
            'name' => 'Otros Libros de Texto',
            'slug' => 'libros-texto', // Slug duplicado
            'description' => 'Otra descripción',
            'icon' => '📖'
        ]);
    }

    /** @test */
    public function it_gets_popular_categories_by_listing_count()
    {
        // Arrange
        $category1 = Category::factory()->create(['name' => 'Más Popular']);
        $category2 = Category::factory()->create(['name' => 'Medianamente Popular']);
        $category3 = Category::factory()->create(['name' => 'Menos Popular']);
        
        // Crear diferentes cantidades de listings
        Listing::factory()->count(5)->create(['category_id' => $category1->id]);
        Listing::factory()->count(3)->create(['category_id' => $category2->id]);
        Listing::factory()->count(1)->create(['category_id' => $category3->id]);

        // Act
        $popularCategories = Category::popular()->take(2)->get();

        // Assert
        $this->assertCount(2, $popularCategories);
        $this->assertEquals('Más Popular', $popularCategories->first()->name);
        $this->assertEquals('Medianamente Popular', $popularCategories->get(1)->name);
    }

    /** @test */
    public function it_provides_route_key_as_slug()
    {
        // Arrange
        $category = Category::factory()->create(['slug' => 'electronicos-gadgets']);

        // Act & Assert
        $this->assertEquals('electronicos-gadgets', $category->getRouteKey());
    }

    /** @test */
    public function it_formats_listing_count_for_display()
    {
        // Arrange
        $category = Category::factory()->create();
        Listing::factory()->count(25)->create(['category_id' => $category->id]);

        // Act
        $formattedCount = $category->getFormattedListingCount();

        // Assert
        $this->assertEquals('25 anuncios', $formattedCount);
    }

    /** @test */
    public function it_formats_single_listing_count_correctly()
    {
        // Arrange
        $category = Category::factory()->create();
        Listing::factory()->create(['category_id' => $category->id]);

        // Act
        $formattedCount = $category->getFormattedListingCount();

        // Assert
        $this->assertEquals('1 anuncio', $formattedCount);
    }
}