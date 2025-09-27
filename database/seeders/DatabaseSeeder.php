<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders
        $this->call([
            CategorySeeder::class,
        ]);

        // Crear usuarios de prueba
        User::factory()->create([
            'name' => 'Admin Usuario',
            'email' => 'admin@marketudec.com',
            'is_moderator' => true,
            'career' => 'Ingeniería en Sistemas',
            'campus' => 'Campus Central',
        ]);

        User::factory()->create([
            'name' => 'Juan Estudiante',
            'email' => 'juan@estudiante.udec.cl',
            'career' => 'Ingeniería Civil',
            'campus' => 'Campus Concepción',
        ]);

        User::factory()->create([
            'name' => 'María Vendedora',
            'email' => 'maria@estudiante.udec.cl',
            'career' => 'Psicología',
            'campus' => 'Campus Los Ángeles',
        ]);

        // Ejecutar seeder de listings después de crear usuarios
        $this->call([
            ListingSeeder::class,
        ]);
    }
}
