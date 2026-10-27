<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Plusieurs listes officielles dans le même référentiel :
     *  - drena    : 25 filles + 25 garçons par DRENA (rang_drena = rang dans la DRENA)
     *  - handicap : meilleurs bacheliers en situation de handicap (rang_drena = rang NATIONAL)
     * Un même élève peut figurer dans les deux listes : l'unicité porte sur (matricule, categorie).
     * L'index composite commence par matricule : il sert aussi aux recherches par matricule.
     * Les lignes existantes prennent la valeur par défaut « drena ».
     */
    public function up(): void
    {
        Schema::table('palmares_bac', function (Blueprint $table) {
            $table->string('categorie', 20)->default('drena')->after('annee_bac')->index();
        });

        Schema::table('palmares_bac', function (Blueprint $table) {
            $table->dropUnique(['matricule']);
            $table->unique(['matricule', 'categorie']);
        });
    }

    public function down(): void
    {
        // Retire d'abord les listes autres que « drena », sinon l'unicité du matricule peut échouer.
        DB::table('palmares_bac')->where('categorie', '!=', 'drena')->delete();

        Schema::table('palmares_bac', function (Blueprint $table) {
            $table->dropUnique(['matricule', 'categorie']);
            $table->unique('matricule');
            $table->dropIndex(['categorie']);
            $table->dropColumn('categorie');
        });
    }
};
