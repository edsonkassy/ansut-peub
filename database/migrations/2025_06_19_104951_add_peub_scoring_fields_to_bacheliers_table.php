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
        Schema::table('bacheliers', function (Blueprint $table) {
            // Champs pour le scoring PEUB
            $table->decimal('score_academique', 5, 2)->nullable()->comment('Score académique (30%)');
            $table->decimal('score_geographique', 5, 2)->nullable()->comment('Score géographique (30%)');
            $table->decimal('score_socio_economique', 5, 2)->nullable()->comment('Score socio-économique (30%)');
            $table->decimal('score_motivations', 5, 2)->nullable()->comment('Score motivations & ambitions (10%)');
            $table->decimal('score_final_peub', 5, 2)->nullable()->comment('Score final PEUB (0-100)');
            $table->integer('rang_peub')->nullable()->comment('Rang dans le classement PEUB');
            $table->json('details_scoring')->nullable()->comment('Détails du calcul du scoring');
            $table->timestamp('date_calcul_scoring')->nullable()->comment('Date du calcul du scoring');
            
            // Champ pour la photo de profil
            $table->string('photo_profil')->nullable()->comment('Photo de profil du candidat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bacheliers', function (Blueprint $table) {
            $table->dropColumn([
                'score_academique',
                'score_geographique', 
                'score_socio_economique',
                'score_motivations',
                'score_final_peub',
                'rang_peub',
                'details_scoring',
                'date_calcul_scoring',
                'photo_profil'
            ]);
        });
    }
};
