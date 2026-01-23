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
        Schema::create('favoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bachelier_id')->constrained()->onDelete('cascade');
            $table->foreignId('opportunite_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Contrainte d'unicité
            $table->unique(['bachelier_id', 'opportunite_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoris');
    }
};
