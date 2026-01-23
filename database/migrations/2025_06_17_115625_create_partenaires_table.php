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
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            
            $table->string('nom_organisation');
            $table->enum('type_organisation', ['entreprise', 'institution_academique', 'ong', 'gouvernement']);
            $table->string('secteur_activite')->nullable();
            $table->string('pays');
            $table->string('ville')->nullable();
            $table->text('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('site_web')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            
            $table->string('personne_contact_nom');
            $table->string('personne_contact_fonction')->nullable();
            $table->string('personne_contact_telephone')->nullable();
            $table->string('personne_contact_email')->nullable();
            
            $table->enum('status_verification', ['pending', 'verified', 'rejected'])->default('pending');
            $table->date('date_verification')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaires');
    }
};
