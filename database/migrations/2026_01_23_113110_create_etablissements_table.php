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
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->string('drena')->nullable()->comment('Direction Régionale de l\'Éducation Nationale');
            $table->string('commune')->nullable()->comment('Commune');
            $table->string('code_etab')->unique()->comment('Code établissement');
            $table->string('etablissement')->comment('Nom de l\'établissement');
            $table->string('type_etab')->nullable()->comment('Type d\'établissement (Lycée, Collège, etc.)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etablissements');
    }
};
