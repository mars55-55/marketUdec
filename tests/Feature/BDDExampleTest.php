<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BDDExampleTest extends TestCase
{
    /**
     * BDD Example: Como desarrollador
     * Quiero demostrar el formato Given-When-Then
     * Para explicar el enfoque orientado al comportamiento
     */

    #[Test]
    public function a_visitor_can_view_the_homepage()
    {
        // Given - Un visitante en la aplicación
        // (No necesita configuración adicional)

        // When - Visita la página principal
        $response = $this->get('/');

        // Then - Ve la página exitosamente
        $response->assertStatus(200);
        $response->assertSee('MarketUdec');
    }

    #[Test]
    public function a_visitor_sees_navigation_elements()
    {
        // Given - Un visitante en la homepage
        
        // When - Carga la página principal
        $response = $this->get('/');

        // Then - Ve los elementos de navegación esenciales
        $response->assertStatus(200);
        $response->assertSee('Iniciar Sesión');
        $response->assertSee('Registrarse'); 
    }

    #[Test]
    public function application_returns_json_for_api_routes()
    {
        // Given - Una aplicación con rutas API
        
        // When - Se hace una petición a una ruta inexistente con accept JSON
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/nonexistent');

        // Then - Retorna una respuesta JSON con error 404
        $response->assertStatus(404);
        $response->assertJson(['message' => true]); // Verifica que hay un mensaje
    }
}