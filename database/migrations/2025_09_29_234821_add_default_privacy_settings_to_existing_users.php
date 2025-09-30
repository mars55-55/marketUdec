<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Configuraciones de privacidad por defecto
        $defaultPrivacySettings = [
            'show_email' => false,
            'show_phone' => false,
            'show_campus' => true,
            'show_career' => true,
            'allow_messages' => true,
            'show_listings_count' => true,
        ];

        // Actualizar todos los usuarios existentes que no tienen configuraciones de privacidad
        User::whereNull('privacy_settings')->update([
            'privacy_settings' => json_encode($defaultPrivacySettings)
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario hacer rollback de esto ya que son configuraciones por defecto
    }
};
