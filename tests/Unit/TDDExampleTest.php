<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class TDDExampleTest extends TestCase
{
    /**
     * Ejemplo de TDD: Test que falla primero (Red)
     * Luego implementamos la funcionalidad (Green)
     * Finalmente refactorizamos (Refactor)
     */

    /** @test */
    public function it_can_format_chilean_price()
    {
        // Red: Este test fallará hasta implementar la función
        $price = 25000;
        $formatted = format_chilean_price($price);
        
        $this->assertEquals('$25.000', $formatted);
    }

    /** @test */
    public function it_handles_zero_price()
    {
        $price = 0;
        $formatted = format_chilean_price($price);
        
        $this->assertEquals('$0', $formatted);
    }

    /** @test */
    public function it_handles_large_numbers()
    {
        $price = 1500000;
        $formatted = format_chilean_price($price);
        
        $this->assertEquals('$1.500.000', $formatted);
    }
}

/**
 * Implementación de la función después de los tests (TDD)
 */
if (!function_exists('format_chilean_price')) {
    function format_chilean_price($price)
    {
        if ($price == 0) {
            return '$0';
        }
        
        return '$' . number_format($price, 0, ',', '.');
    }
}