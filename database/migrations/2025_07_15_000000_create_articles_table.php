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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->longText('contenu');
            $table->text('resume')->nullable();
            $table->string('image_principale')->nullable();
            $table->string('categorie')->default('actualite');
            $table->json('tags')->nullable();
            $table->foreignId('auteur_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('date_publication')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('temps_lecture')->nullable(); // en minutes
            $table->integer('vues')->default(0);
            $table->boolean('featured')->default(false);
            $table->integer('ordre_affichage')->default(0);
            $table->timestamps();

            // Index pour les requêtes courantes
            $table->index(['status', 'date_publication']);
            $table->index(['categorie', 'status']);
            $table->index(['featured', 'status']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
}; 