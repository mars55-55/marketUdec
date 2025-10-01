<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Conversation;
use App\Models\Message;

class UserInteractionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * BDD: Como estudiante universitario
     * Quiero interactuar con otros usuarios (favoritos, mensajes, etc.)
     * Para facilitar la compra y venta de productos
     */

    /** @test */
    public function a_user_can_add_listing_to_favorites()
    {
        // Given - Un usuario autenticado y un anuncio
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $listing = Listing::factory()->create([
            'category_id' => $category->id,
            'status' => 'active'
        ]);

        $this->actingAs($user);

        // When - Agrega el anuncio a favoritos
        $response = $this->post(route('favorites.store'), [
            'listing_id' => $listing->id
        ]);

        // Then - El favorito se crea exitosamente
        $response->assertStatus(200);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'listing_id' => $listing->id
        ]);
    }

    /** @test */
    public function a_user_can_remove_listing_from_favorites()
    {
        // Given - Un usuario con un anuncio en favoritos
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $listing = Listing::factory()->create(['category_id' => $category->id]);
        
        // Agregar a favoritos primero
        $user->favorites()->create(['listing_id' => $listing->id]);
        $this->actingAs($user);

        // When - Remueve de favoritos
        $response = $this->delete(route('favorites.destroy'), [
            'listing_id' => $listing->id
        ]);

        // Then - El favorito se elimina
        $response->assertStatus(200);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'listing_id' => $listing->id
        ]);
    }

    /** @test */
    public function a_user_can_toggle_favorite_status()
    {
        // Given
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $listing = Listing::factory()->create(['category_id' => $category->id]);
        $this->actingAs($user);

        // When - Toggle primera vez (agregar)
        $response = $this->post(route('favorites.toggle'), [
            'listing_id' => $listing->id
        ]);

        // Then - Se agrega a favoritos
        $response->assertJson(['is_favorited' => true]);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'listing_id' => $listing->id
        ]);

        // When - Toggle segunda vez (remover)
        $response = $this->post(route('favorites.toggle'), [
            'listing_id' => $listing->id
        ]);

        // Then - Se remueve de favoritos
        $response->assertJson(['is_favorited' => false]);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'listing_id' => $listing->id
        ]);
    }

    /** @test */
    public function a_user_can_view_their_favorite_listings()
    {
        // Given - Usuario con varios favoritos
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $favoriteListing1 = Listing::factory()->create([
            'title' => 'Libro Favorito 1',
            'category_id' => $category->id
        ]);
        
        $favoriteListing2 = Listing::factory()->create([
            'title' => 'Libro Favorito 2', 
            'category_id' => $category->id
        ]);
        
        $otherListing = Listing::factory()->create([
            'title' => 'Libro No Favorito',
            'category_id' => $category->id
        ]);

        $user->favorites()->create(['listing_id' => $favoriteListing1->id]);
        $user->favorites()->create(['listing_id' => $favoriteListing2->id]);
        $this->actingAs($user);

        // When - Ve su página de favoritos
        $response = $this->get(route('favorites.index'));

        // Then - Solo ve sus favoritos
        $response->assertStatus(200);
        $response->assertSeeText('Libro Favorito 1');
        $response->assertSeeText('Libro Favorito 2');
        $response->assertDontSeeText('Libro No Favorito');
    }

    /** @test */
    public function a_user_can_start_conversation_with_seller()
    {
        // Given - Comprador y vendedor
        $buyer = User::factory()->create(['name' => 'Comprador']);
        $seller = User::factory()->create(['name' => 'Vendedor']);
        $category = Category::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'title' => 'Producto a Comprar'
        ]);

        $this->actingAs($buyer);

        // When - Inicia conversación
        $response = $this->post(route('conversations.store'), [
            'user2_id' => $seller->id,
            'listing_id' => $listing->id,
            'message' => '¡Hola! Me interesa tu producto. ¿Está disponible?'
        ]);

        // Then - Se crea la conversación y mensaje
        $response->assertRedirect();
        
        $this->assertDatabaseHas('conversations', [
            'user1_id' => $buyer->id,
            'user2_id' => $seller->id,
            'listing_id' => $listing->id
        ]);
        
        $this->assertDatabaseHas('messages', [
            'user_id' => $buyer->id,
            'content' => '¡Hola! Me interesa tu producto. ¿Está disponible?'
        ]);
    }

    /** @test */
    public function a_user_cannot_start_conversation_with_themselves()
    {
        // Given - Usuario que es dueño del anuncio
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $this->actingAs($user);

        // When - Intenta crear conversación consigo mismo
        $response = $this->post(route('conversations.store'), [
            'user2_id' => $user->id,
            'listing_id' => $listing->id,
            'message' => 'Mensaje a mí mismo'
        ]);

        // Then - Se rechaza la acción
        $response->assertSessionHasErrors();
        $this->assertEquals(0, Conversation::count());
    }

    /** @test */
    public function users_can_exchange_messages_in_conversation()
    {
        // Given - Conversación existente
        $user1 = User::factory()->create(['name' => 'Usuario 1']);
        $user2 = User::factory()->create(['name' => 'Usuario 2']);
        $category = Category::factory()->create();
        $listing = Listing::factory()->create(['category_id' => $category->id]);
        
        $conversation = Conversation::create([
            'user1_id' => $user1->id,
            'user2_id' => $user2->id,
            'listing_id' => $listing->id
        ]);

        // When - Usuario 1 envía mensaje
        $this->actingAs($user1);
        $response = $this->post(route('messages.store'), [
            'conversation_id' => $conversation->id,
            'message' => 'Hola, ¿cómo estás?'
        ]);

        // Then - Mensaje se crea exitosamente
        $response->assertStatus(200);
        
        // When - Usuario 2 responde
        $this->actingAs($user2);
        $response = $this->post(route('messages.store'), [
            'conversation_id' => $conversation->id,
            'message' => '¡Muy bien, gracias!'
        ]);

        // Then - Ambos mensajes existen
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $user1->id,
            'content' => 'Hola, ¿cómo estás?'
        ]);
        
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $user2->id,
            'content' => '¡Muy bien, gracias!'
        ]);
    }

    /** @test */
    public function a_user_can_view_their_conversations()
    {
        // Given - Usuario con múltiples conversaciones
        $user = User::factory()->create();
        $otherUser1 = User::factory()->create(['name' => 'Contacto 1']);
        $otherUser2 = User::factory()->create(['name' => 'Contacto 2']);
        $category = Category::factory()->create();
        
        $listing1 = Listing::factory()->create(['category_id' => $category->id]);
        $listing2 = Listing::factory()->create(['category_id' => $category->id]);

        $conversation1 = Conversation::create([
            'user1_id' => $user->id,
            'user2_id' => $otherUser1->id,
            'listing_id' => $listing1->id
        ]);
        
        $conversation2 = Conversation::create([
            'user1_id' => $otherUser2->id,
            'user2_id' => $user->id,
            'listing_id' => $listing2->id
        ]);

        $this->actingAs($user);

        // When - Ve sus conversaciones
        $response = $this->get(route('conversations.index'));

        // Then - Ve todas sus conversaciones
        $response->assertStatus(200);
        $response->assertSeeText('Contacto 1');
        $response->assertSeeText('Contacto 2');
    }

    /** @test */
    public function only_conversation_participants_can_access_conversation()
    {
        // Given - Conversación entre dos usuarios
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $outsider = User::factory()->create();
        $category = Category::factory()->create();
        $listing = Listing::factory()->create(['category_id' => $category->id]);
        
        $conversation = Conversation::create([
            'user1_id' => $user1->id,
            'user2_id' => $user2->id,
            'listing_id' => $listing->id
        ]);

        // When - Usuario externo intenta acceder
        $this->actingAs($outsider);
        $response = $this->get(route('conversations.show', $conversation));

        // Then - Se le niega el acceso
        $response->assertStatus(403);
    }

    /** @test */
    public function a_user_can_mark_listing_as_sold()
    {
        // Given - Usuario propietario de anuncio
        $seller = User::factory()->create();
        $category = Category::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'status' => 'active'
        ]);

        $this->actingAs($seller);

        // When - Marca como vendido
        $response = $this->post(route('listings.mark-sold', $listing));

        // Then - Estado se actualiza
        $response->assertRedirect();
        $this->assertEquals('sold', $listing->fresh()->status);
    }

    /** @test */
    public function only_listing_owner_can_mark_as_sold()
    {
        // Given - Usuario que no es propietario
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'status' => 'active'
        ]);

        $this->actingAs($otherUser);

        // When - Intenta marcar como vendido
        $response = $this->post(route('listings.mark-sold', $listing));

        // Then - Se le niega el acceso
        $response->assertStatus(403);
        $this->assertEquals('active', $listing->fresh()->status);
    }
}