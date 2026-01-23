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
        Schema::table('partenaires', function (Blueprint $table) {
            // Renommer pays en region
            $table->renameColumn('pays', 'region');
            // Rendre user_id nullable pour les candidatures en attente
            $table->foreignId('user_id')->nullable()->change();
        });
        
        Schema::table('partenaires', function (Blueprint $table) {
            // Modifier ville pour la rendre obligatoire et la renommer en commune
            $table->string('ville')->nullable(false)->change();
        });
        
        Schema::table('partenaires', function (Blueprint $table) {
            // Renommer ville en commune
            $table->renameColumn('ville', 'commune');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partenaires', function (Blueprint $table) {
            // Renommer commune en ville
            $table->renameColumn('commune', 'ville');
        });
        
        Schema::table('partenaires', function (Blueprint $table) {
            // Remettre ville comme nullable
            $table->string('ville')->nullable()->change();
        });
        
        Schema::table('partenaires', function (Blueprint $table) {
            // Renommer region en pays
            $table->renameColumn('region', 'pays');
        });
    }
};
