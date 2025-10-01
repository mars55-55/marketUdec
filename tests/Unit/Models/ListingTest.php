<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use App\Models\ListingImage;
use App\Models\Favorite;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_listing_with_required_fields()
    {
        // Arrange - TDD: Definir datos mínimos requeridos
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $listingData = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Libro de Cálculo Stewart',
            'description' => 'Libro en excelente estado',
            'price' => 25000,
            'condition' => 'como_nuevo',
            'status' => 'active'
        ];

        // Act
        $listing = Listing::create($listingData);

        // Assert
        $this->assertInstanceOf(Listing::class, $listing);
        $this->assertEquals('Libro de Cálculo Stewart', $listing->title);
        $this->assertEquals(25000, $listing->price);
        $this->assertEquals('active', $listing->status);
        $this->assertDatabaseHas('listings', $listingData);
    }

    /** @test */
    public function it_belongs_to_user_and_category()
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create(['name' => 'Libros']);
        $listing = Listing::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        // Act & Assert - Verificar relaciones
        $this->assertInstanceOf(User::class, $listing->user);
        $this->assertEquals($user->id, $listing->user->id);
        
        $this->assertInstanceOf(Category::class, $listing->category);
        $this->assertEquals('Libros', $listing->category->name);
    }

    /** @test */
    public function it_can_have_multiple_images()
    {
        // Arrange
        $listing = Listing::factory()->create();

        // Act - Crear imágenes para el listing
        $image1 = ListingImage::factory()->create([
            'listing_id' => $listing->id,
            'path' => 'images/listing1_1.jpg',
            'is_primary' => true
        ]);
        
        $image2 = ListingImage::factory()->create([
            'listing_id' => $listing->id,
            'path' => 'images/listing1_2.jpg',
            'is_primary' => false
        ]);

        // Assert
        $this->assertCount(2, $listing->images);
        $this->assertTrue($listing->images->contains($image1));
        $this->assertTrue($listing->images->contains($image2));
    }

    /** @test */
    public function it_gets_primary_image_correctly()
    {
        // Arrange
        $listing = Listing::factory()->create();
        
        $secondaryImage = ListingImage::factory()->create([
            'listing_id' => $listing->id,
            'is_primary' => false
        ]);
        
        $primaryImage = ListingImage::factory()->create([
            'listing_id' => $listing->id,
            'is_primary' => true
        ]);

        // Act
        $result = $listing->primaryImage;

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($primaryImage->id, $result->id);
        $this->assertTrue($result->is_primary);
    }

    /** @test */
    public function it_can_be_favorited_by_users()
    {
        // Arrange
        $listing = Listing::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Act
        Favorite::create(['user_id' => $user1->id, 'listing_id' => $listing->id]);
        Favorite::create(['user_id' => $user2->id, 'listing_id' => $listing->id]);

        // Assert
        $this->assertCount(2, $listing->favorites);
        $this->assertEquals(2, $listing->favorites()->count());
    }

    /** @test */
    public function it_increments_views_counter()
    {
        // Arrange
        $listing = Listing::factory()->create(['views' => 5]);

        // Act
        $listing->incrementViews();

        // Assert
        $this->assertEquals(6, $listing->fresh()->views);
    }

    /** @test */
    public function it_validates_condition_values()
    {
        // TDD: Test de validación de reglas de negocio
        $validConditions = ['nuevo', 'como_nuevo', 'bueno', 'aceptable', 'para_repuestos'];
        
        foreach ($validConditions as $condition) {
            $listing = Listing::factory()->make(['condition' => $condition]);
            $this->assertEquals($condition, $listing->condition);
        }
    }

    /** @test */
    public function it_validates_status_values()
    {
        // Arrange & Act & Assert
        $validStatuses = ['active', 'paused', 'sold', 'blocked'];
        
        foreach ($validStatuses as $status) {
            $listing = Listing::factory()->make(['status' => $status]);
            $this->assertEquals($status, $listing->status);
        }
    }

    /** @test */
    public function it_knows_if_it_is_active()
    {
        // Arrange
        $activeListing = Listing::factory()->create(['status' => 'active']);
        $pausedListing = Listing::factory()->create(['status' => 'paused']);
        $soldListing = Listing::factory()->create(['status' => 'sold']);

        // Act & Assert
        $this->assertTrue($activeListing->isActive());
        $this->assertFalse($pausedListing->isActive());
        $this->assertFalse($soldListing->isActive());
    }

    /** @test */
    public function it_knows_if_it_is_expired()
    {
        // Arrange
        $futureDate = Carbon::now()->addDays(10);
        $pastDate = Carbon::now()->subDays(5);
        
        $activeListing = Listing::factory()->create(['expires_at' => $futureDate]);
        $expiredListing = Listing::factory()->create(['expires_at' => $pastDate]);

        // Act & Assert
        $this->assertFalse($activeListing->isExpired());
        $this->assertTrue($expiredListing->isExpired());
    }

    /** @test */
    public function it_formats_price_correctly()
    {
        // Arrange
        $listing = Listing::factory()->create(['price' => 25000]);

        // Act & Assert
        $this->assertEquals('$25.000', $listing->getFormattedPrice());
    }

    /** @test */
    public function it_can_be_reported()
    {
        // Arrange
        $listing = Listing::factory()->create();
        $reporter = User::factory()->create();

        // Act
        $report = Report::factory()->create([
            'listing_id' => $listing->id,
            'reporter_id' => $reporter->id,
            'reason' => 'inappropriate'
        ]);

        // Assert
        $this->assertCount(1, $listing->reports);
        $this->assertTrue($listing->reports->contains($report));
    }

    /** @test */
    public function it_scopes_by_category()
    {
        // Arrange
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        
        $listing1 = Listing::factory()->create(['category_id' => $category1->id]);
        $listing2 = Listing::factory()->create(['category_id' => $category2->id]);

        // Act
        $filteredListings = Listing::byCategory($category1->id)->get();

        // Assert
        $this->assertCount(1, $filteredListings);
        $this->assertTrue($filteredListings->contains($listing1));
        $this->assertFalse($filteredListings->contains($listing2));
    }

    /** @test */
    public function it_scopes_by_price_range()
    {
        // Arrange
        $cheapListing = Listing::factory()->create(['price' => 10000]);
        $midListing = Listing::factory()->create(['price' => 25000]);
        $expensiveListing = Listing::factory()->create(['price' => 50000]);

        // Act
        $filteredListings = Listing::priceRange(15000, 30000)->get();

        // Assert
        $this->assertCount(1, $filteredListings);
        $this->assertTrue($filteredListings->contains($midListing));
        $this->assertFalse($filteredListings->contains($cheapListing));
        $this->assertFalse($filteredListings->contains($expensiveListing));
    }

    /** @test */
    public function it_searches_by_title_and_description()
    {
        // Arrange
        $listing1 = Listing::factory()->create([
            'title' => 'Libro de Cálculo',
            'description' => 'Matemáticas avanzadas'
        ]);
        
        $listing2 = Listing::factory()->create([
            'title' => 'Computadora portátil',
            'description' => 'Para programación'
        ]);

        // Act
        $results = Listing::search('Cálculo')->get();

        // Assert
        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($listing1));
        $this->assertFalse($results->contains($listing2));
    }
}