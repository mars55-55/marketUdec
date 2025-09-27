<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('career')->nullable(); // Carrera
            $table->string('campus')->nullable(); // Sede
            $table->text('bio')->nullable(); // Biografía
            $table->string('phone')->nullable(); // Teléfono
            $table->string('profile_photo')->nullable(); // Foto de perfil
            $table->decimal('rating', 3, 2)->default(0); // Rating promedio
            $table->integer('rating_count')->default(0); // Cantidad de calificaciones
            $table->boolean('is_moderator')->default(false); // Es moderador
            $table->boolean('is_blocked')->default(false); // Está bloqueado
            $table->json('privacy_settings')->nullable(); // Configuración de privacidad
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'career', 'campus', 'bio', 'phone', 'profile_photo',
                'rating', 'rating_count', 'is_moderator', 'is_blocked',
                'privacy_settings'
            ]);
        });
    }
};
