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
        Schema::create('opportunites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partenaire_id')->constrained()->onDelete('cascade');
            
            $table->string('titre');
            $table->enum('type', ['bourse', 'stage', 'emploi', 'formation', 'concours', 'event', 'promotion']);
            $table->text('description');
            $table->json('competences_requises')->nullable();
            $table->json('criteres_eligibilite')->nullable();
            $table->string('pays')->nullable();
            $table->string('ville')->nullable();
            $table->string('duree')->nullable();
            $table->string('remuneration')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->date('date_limite_candidature');
            $table->integer('nombre_places')->nullable();
            $table->json('documents_requis')->nullable();
            $table->string('lien_externe')->nullable();
            $table->enum('status', ['draft', 'published', 'closed', 'archived'])->default('draft');
            $table->integer('vues')->default(0);
            $table->integer('candidatures_count')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunites');
    }
};
