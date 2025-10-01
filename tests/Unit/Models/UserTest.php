<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user_with_required_fields()
    {
        // Arrange - TDD: Primero definimos qué esperamos
        $userData = [
            'name' => 'Juan Estudiante',
            'email' => 'juan@udec.cl',
            'password' => bcrypt('password123'),
            'career' => 'Ingeniería en Sistemas',
            'campus' => 'Campus Concepción'
        ];

        // Act - Ejecutamos la acción
        $user = User::create($userData);

        // Assert - Verificamos el resultado
        $this->assertDatabaseHas('users', [
            'name' => 'Juan Estudiante',
            'email' => 'juan@udec.cl',
            'career' => 'Ingeniería en Sistemas',
            'campus' => 'Campus Concepción'
        ]);
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Juan Estudiante', $user->name);
    }

    /** @test */
    public function it_requires_name_email_and_password()
    {
        // TDD: Test que valida reglas de negocio
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        User::create([
            // Sin campos requeridos
            'career' => 'Ingeniería Civil'
        ]);
    }

    /** @test */
    public function it_has_many_listings()
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        // Act
        $listing1 = Listing::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $listing2 = Listing::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);

        // Assert - Verificamos relaciones
        $this->assertCount(2, $user->listings);
        $this->assertTrue($user->listings->contains($listing1));
        $this->assertTrue($user->listings->contains($listing2));
    }

    /** @test */
    public function it_can_favorite_listings()
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $listing = Listing::factory()->create(['category_id' => $category->id]);

        // Act
        $user->favorites()->create(['listing_id' => $listing->id]);

        // Assert
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'listing_id' => $listing->id
        ]);
        
        $this->assertTrue($user->favorites()->where('listing_id', $listing->id)->exists());
    }

    /** @test */
    public function it_calculates_average_rating_correctly()
    {
        // Arrange
        $user = User::factory()->create();
        $reviewer1 = User::factory()->create();
        $reviewer2 = User::factory()->create();
        $reviewer3 = User::factory()->create();

        // Act - Crear reseñas con diferentes calificaciones
        Review::factory()->create([
            'user_id' => $user->id,
            'reviewer_id' => $reviewer1->id,
            'rating' => 5
        ]);
        
        Review::factory()->create([
            'user_id' => $user->id,
            'reviewer_id' => $reviewer2->id,
            'rating' => 3
        ]);
        
        Review::factory()->create([
            'user_id' => $user->id,
            'reviewer_id' => $reviewer3->id,
            'rating' => 4
        ]);

        // Assert - Promedio debe ser 4.0
        $this->assertEquals(4.0, $user->averageRating());
        $this->assertEquals(3, $user->totalReviews());
    }

    /** @test */
    public function it_knows_if_user_is_moderator()
    {
        // Arrange & Act
        $regularUser = User::factory()->create(['is_moderator' => false]);
        $moderatorUser = User::factory()->create(['is_moderator' => true]);

        // Assert
        $this->assertFalse($regularUser->isModerator());
        $this->assertTrue($moderatorUser->isModerator());
    }

    /** @test */
    public function it_formats_full_university_info()
    {
        // Arrange
        $user = User::factory()->create([
            'career' => 'Ingeniería en Sistemas',
            'campus' => 'Campus Concepción'
        ]);

        // Act & Assert
        $expectedInfo = 'Ingeniería en Sistemas - Campus Concepción';
        $this->assertEquals($expectedInfo, $user->getFullUniversityInfo());
    }

    /** @test */
    public function it_can_be_converted_to_array_safely()
    {
        // Arrange
        $user = User::factory()->create([
            'password' => bcrypt('secret123')
        ]);

        // Act
        $userArray = $user->toArray();

        // Assert - Password no debe estar expuesto
        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayHasKey('name', $userArray);
        $this->assertArrayHasKey('email', $userArray);
    }
}