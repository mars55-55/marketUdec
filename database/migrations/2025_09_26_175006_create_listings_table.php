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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('condition', ['nuevo', 'como_nuevo', 'bueno', 'aceptable', 'malo']);
            $table->enum('status', ['active', 'paused', 'sold', 'blocked'])->default('active');
            $table->string('location')->nullable();
            $table->json('tags')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('is_negotiable')->default(true);
            $table->boolean('allows_exchange')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index(['category_id', 'status']);
            
            // Detectar el driver de base de datos actual
            $driver = Schema::getConnection()->getDriverName();
            
            // Solo crear índice fulltext para MySQL y PostgreSQL
            if ($driver === 'mysql' || $driver === 'pgsql') {
                $table->fullText(['title', 'description']);
            } else {
                // Para SQLite, crear índices regulares
                $table->index('title');
                $table->index('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
