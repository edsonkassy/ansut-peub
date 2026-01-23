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
        Schema::create('partenaire_opportunite_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partenaire_id')->constrained()->onDelete('cascade');
            $table->string('type_opportunite');
            $table->timestamps();

            $table->unique(['partenaire_id', 'type_opportunite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaire_opportunite_types');
    }
}; 