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
        User::updateOrCreate(
            ['email' => 'admin@marketudec.com'],
            [
                'name' => 'Admin Usuario',
                'is_moderator' => true,
                'career' => 'Ingeniería en Sistemas',
                'campus' => 'Campus Central',
                'password' => bcrypt('password'), // Default password
            ]
        );

        User::updateOrCreate(
            ['email' => 'juan@estudiante.udec.cl'],
            [
                'name' => 'Juan Estudiante',
                'career' => 'Ingeniería Civil',
                'campus' => 'Campus Concepción',
                'password' => bcrypt('password'), // Default password
            ]
        );

        User::updateOrCreate(
            ['email' => 'maria@estudiante.udec.cl'],
            [
                'name' => 'María Vendedora',
                'career' => 'Psicología',
                'campus' => 'Campus Los Ángeles',
                'password' => bcrypt('password'), // Default password
            ]
        );

        // Ejecutar seeder de listings después de crear usuarios
        $this->call([
            ListingSeeder::class,
        ]);
    }
}
