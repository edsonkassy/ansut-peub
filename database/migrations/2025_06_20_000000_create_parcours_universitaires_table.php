<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcours_universitaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bachelier_id')->constrained()->onDelete('cascade');
            $table->string('universite_nom'); // Nom de l'université
            $table->string('pays'); // Pays (ex: 'Côte d'Ivoire' pour local, 'France' pour international)
            $table->enum('niveau', ['bts', 'licence', 'master', 'doctorat', 'autre']); // Niveau universitaire
            $table->string('annee_academique'); // Ex: '2024-2025'
            $table->decimal('performance', 5, 2)->nullable(); // Performance résumée (ex: moyenne sur 20)
            $table->string('mention')->nullable(); // Ex: 'bien', 'très bien' (optionnel)
            $table->string('attestation_admission_file')->nullable(); // Chemin du fichier uploadé
            $table->json('extracted_data')->nullable(); // Données extraites par IA (ex: dates, notes du fichier)
            $table->enum('statut', ['en_cours', 'termine', 'abandonne'])->default('en_cours');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcours_universitaires');
    }
}; 