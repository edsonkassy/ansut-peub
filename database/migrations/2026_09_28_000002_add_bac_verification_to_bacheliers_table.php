<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bacheliers', function (Blueprint $table) {
            $table->boolean('bac_verifie')->default(false)->index();
            $table->timestamp('bac_verifie_at')->nullable();
            $table->foreignId('palmares_bac_id')->nullable()->constrained('palmares_bac')->nullOnDelete();
            $table->string('bac_verification_statut', 20)->nullable(); // verifie | divergent | absent
            $table->text('bac_verification_note')->nullable();
            // Consentement (libre, retirable) à l'affichage « Prénom + initiale » sur la carte publique
            $table->boolean('consentement_carte_publique')->default(false);
            $table->timestamp('consentement_carte_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bacheliers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('palmares_bac_id');
            $table->dropColumn(['bac_verifie', 'bac_verifie_at', 'bac_verification_statut', 'bac_verification_note',
                'consentement_carte_publique', 'consentement_carte_at']);
        });
    }
};
