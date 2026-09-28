<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Référentiel officiel : liste des meilleurs bacheliers par DRENA (source : ministère)
        Schema::create('palmares_bac', function (Blueprint $table) {
            $table->id();
            $table->string('matricule', 20)->unique();
            $table->string('nom');
            $table->string('prenoms');
            $table->date('date_naissance');
            $table->string('lieu_naissance')->nullable();
            $table->string('drena', 60)->index();
            $table->enum('sexe', ['M', 'F']);
            $table->string('serie', 10);
            $table->string('mention', 20)->nullable(); // passable | assez_bien | bien | tres_bien | null
            $table->string('etablissement')->nullable();
            $table->unsignedSmallInteger('points'); // note sur 400
            $table->unsignedSmallInteger('rang_drena');
            $table->year('annee_bac')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('palmares_bac');
    }
};
