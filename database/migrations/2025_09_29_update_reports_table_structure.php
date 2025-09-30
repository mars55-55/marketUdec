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
        Schema::table('reports', function (Blueprint $table) {
            // Actualizar el campo type para incluir los valores correctos
            $table->dropColumn('type');
        });
        
        Schema::table('reports', function (Blueprint $table) {
            // Agregar campo type con los valores correctos
            $table->enum('type', ['listing', 'user'])->after('reported_user_id');
            
            // Agregar campo description
            $table->text('description')->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('description');
        });
        
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('type', ['spam', 'inappropriate', 'fraud', 'duplicate', 'other'])->after('reported_user_id');
        });
    }
};