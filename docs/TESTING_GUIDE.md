# Guía Completa de Pruebas TDD y BDD para MarketUdec

## 📋 Introducción

Esta guía explica la implementación de pruebas de calidad usando **TDD (Test-Driven Development)** y **BDD (Behavior-Driven Development)** en el proyecto MarketUdec, un marketplace universitario desarrollado en Laravel.

## 🎯 Diferencias Clave: TDD vs BDD

### TDD (Test-Driven Development)
- **Enfoque**: "¿El código funciona correctamente?"
- **Nivel**: Pruebas unitarias (modelos, servicios, validaciones)
- **Estructura**: Arrange → Act → Assert
- **Objetivo**: Garantizar que cada unidad de código funcione según especificaciones

### BDD (Behavior-Driven Development)
- **Enfoque**: "¿El sistema cumple los requisitos del negocio?"
- **Nivel**: Pruebas de comportamiento (flujos completos de usuario)
- **Estructura**: Given → When → Then
- **Objetivo**: Validar que el sistema se comporta como espera el usuario

## 🏗️ Estructura de Pruebas Implementadas

```
tests/
├── Unit/                     # Pruebas TDD
│   └── Models/
│       ├── UserTest.php      # Pruebas del modelo User
│       ├── ListingTest.php   # Pruebas del modelo Listing  
│       └── CategoryTest.php  # Pruebas del modelo Category
└── Feature/                  # Pruebas BDD
    ├── CreateListingTest.php    # Flujo de creación de anuncios
    ├── SearchListingsTest.php   # Flujo de búsqueda y filtros
    └── UserInteractionsTest.php # Interacciones entre usuarios
```

## 🧪 Ejemplos de Pruebas TDD

### UserTest.php - Validaciones de Modelo
```php
/** @test */
public function it_can_create_a_user_with_required_fields()
{
    // Arrange - Preparar datos de prueba
    $userData = [
        'name' => 'Juan Estudiante',
        'email' => 'juan@udec.cl',
        'password' => bcrypt('password123'),
        'career' => 'Ingeniería en Sistemas',
        'campus' => 'Campus Concepción'
    ];

    // Act - Ejecutar la acción a probar
    $user = User::create($userData);

    // Assert - Verificar el resultado
    $this->assertInstanceOf(User::class, $user);
    $this->assertEquals('Juan Estudiante', $user->name);
}
```

### ListingTest.php - Relaciones y Scopes
```php
/** @test */
public function it_scopes_by_price_range()
{
    // Arrange - Crear listings con diferentes precios
    $cheapListing = Listing::factory()->create(['price' => 10000]);
    $midListing = Listing::factory()->create(['price' => 25000]);
    $expensiveListing = Listing::factory()->create(['price' => 50000]);

    // Act - Aplicar filtro de precio
    $filteredListings = Listing::priceRange(15000, 30000)->get();

    // Assert - Verificar que solo se incluyan los del rango
    $this->assertCount(1, $filteredListings);
    $this->assertTrue($filteredListings->contains($midListing));
}
```

## 🎭 Ejemplos de Pruebas BDD

### CreateListingTest.php - Flujo de Usuario
```php
/** @test */
public function a_student_can_create_a_listing_when_authenticated()
{
    // Given - Un estudiante autenticado con categoría disponible
    $user = User::factory()->create(['career' => 'Ingeniería Civil']);
    $category = Category::factory()->create(['is_active' => true]);
    $this->actingAs($user);

    // When - Intenta crear un anuncio
    $response = $this->post(route('listings.store'), [
        'category_id' => $category->id,
        'title' => 'Libro de Cálculo Stewart',
        'description' => 'En excelente estado',
        'price' => 35000,
        'condition' => 'como_nuevo'
    ]);

    // Then - El anuncio se crea exitosamente
    $response->assertRedirect();
    $this->assertDatabaseHas('listings', [
        'title' => 'Libro de Cálculo Stewart',
        'status' => 'active'
    ]);
}
```

### SearchListingsTest.php - Comportamiento de Búsqueda
```php
/** @test */
public function a_user_can_search_listings_by_title()
{
    // Given - Varios anuncios en la plataforma
    $calculusBook = Listing::factory()->create([
        'title' => 'Libro de Cálculo Stewart',
        'status' => 'active'
    ]);
    
    $physicsBook = Listing::factory()->create([
        'title' => 'Libro de Física Serway', 
        'status' => 'active'
    ]);

    // When - Busca por término específico
    $response = $this->get(route('search', ['q' => 'Cálculo']));

    // Then - Solo encuentra el libro de cálculo
    $response->assertSeeText('Cálculo Stewart');
    $response->assertDontSeeText('Física Serway');
}
```

## ⚙️ Configuración de Pruebas

### phpunit.xml - Configuración Principal
```xml
<testsuites>
    <testsuite name="Unit">
        <directory>tests/Unit</directory>
    </testsuite>
    <testsuite name="Feature">
        <directory>tests/Feature</directory>
    </testsuite>
</testsuites>
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

## 🏭 Factories para Datos de Prueba

### CategoryFactory.php
```php
public function definition(): array
{
    $name = $this->faker->randomElement([
        'Libros de Texto', 'Electrónicos', 'Deportes'
    ]);

    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'description' => $this->faker->sentence(10),
        'is_active' => true,
    ];
}
```

## 📊 Casos de Prueba Implementados

### Pruebas TDD (Unit)

#### UserTest
- ✅ Creación de usuarios con campos requeridos
- ✅ Validación de campos obligatorios
- ✅ Relaciones con listings y favoritos
- ✅ Cálculo de promedio de calificaciones
- ✅ Verificación de permisos de moderador
- ✅ Formateo de información universitaria
- ✅ Serialización segura (sin password)

#### ListingTest
- ✅ Creación con campos requeridos
- ✅ Relaciones con User y Category
- ✅ Manejo de múltiples imágenes
- ✅ Imagen principal
- ✅ Sistema de favoritos
- ✅ Contador de vistas
- ✅ Validación de estados y condiciones
- ✅ Verificación de expiración
- ✅ Formateo de precios
- ✅ Scopes de búsqueda y filtros

#### CategoryTest
- ✅ Creación con campos requeridos
- ✅ Generación automática de slug
- ✅ Relación con listings
- ✅ Conteo de listings activos
- ✅ Verificación de estado activo
- ✅ Scopes de filtrado
- ✅ Búsqueda por slug
- ✅ Categorías populares
- ✅ Formateo de contadores

### Pruebas BDD (Feature)

#### CreateListingTest
- ✅ Usuario autenticado puede crear anuncios
- ✅ Invitados no pueden crear anuncios
- ✅ Validación de campos requeridos
- ✅ Validación de precios
- ✅ Valores por defecto
- ✅ Campos opcionales completos
- ✅ Redirección después de creación
- ✅ Mensajes de éxito
- ✅ Validación de condiciones

#### SearchListingsTest
- ✅ Búsqueda por título
- ✅ Búsqueda por descripción
- ✅ Filtros por categoría
- ✅ Filtros por rango de precio
- ✅ Combinación de múltiples filtros
- ✅ Solo anuncios activos/vendidos visibles
- ✅ Prioridad de anuncios activos
- ✅ Manejo de resultados vacíos
- ✅ Paginación de resultados
- ✅ Persistencia de parámetros en paginación

#### UserInteractionsTest
- ✅ Agregar/quitar favoritos
- ✅ Toggle de favoritos
- ✅ Ver lista de favoritos
- ✅ Iniciar conversaciones
- ✅ Prevenir auto-conversaciones
- ✅ Intercambio de mensajes
- ✅ Ver conversaciones propias
- ✅ Control de acceso a conversaciones
- ✅ Marcar anuncios como vendidos
- ✅ Autorización de propietarios

## 🚀 Comandos de Ejecución

### Ejecutar todas las pruebas
```bash
php artisan test
```

### Solo pruebas unitarias (TDD)
```bash
php artisan test tests/Unit
```

### Solo pruebas de feature (BDD)
```bash
php artisan test tests/Feature
```

### Con cobertura de código
```bash
php artisan test --coverage
```

### Prueba específica
```bash
php artisan test tests/Unit/Models/UserTest.php
```

## 📈 Beneficios de esta Estrategia

### Para TDD:
- **Detección temprana de bugs** en modelos y lógica de negocio
- **Documentación viva** de cómo deben funcionar los componentes
- **Refactoring seguro** con confianza en los cambios
- **Código más limpio** y modular

### Para BDD:
- **Validación de requisitos** del negocio
- **Comprensión compartida** entre desarrolladores y stakeholders
- **Pruebas de integración** de flujos completos
- **Confianza en despliegues** a producción

## 🎯 Próximos Pasos Recomendados

1. **Ampliar cobertura de pruebas**:
   - Modelos faltantes (Message, Conversation, Report)
   - Controladores complejos (AdminController)
   - Middleware personalizado

2. **Pruebas de integración**:
   - APIs externas
   - Procesamiento de imágenes
   - Notificaciones por email

3. **Pruebas de rendimiento**:
   - Carga de páginas con muchos anuncios
   - Búsquedas complejas
   - Consultas N+1

4. **Automatización CI/CD**:
   - GitHub Actions
   - Ejecución automática en pull requests
   - Reportes de cobertura

Esta estrategia de pruebas proporciona una base sólida para mantener la calidad del código en MarketUdec mientras facilita el desarrollo continuo y la incorporación de nuevas funcionalidades.