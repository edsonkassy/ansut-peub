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
            // Données extraites de la pièce d'identité
            $table->json('piece_identite_extracted_data')->nullable()->after('piece_identite_file');
            
            // Données extraites de la collante du BAC
            $table->json('collante_bac_extracted_data')->nullable()->after('collante_bac_file');
            
            // Scoring IA des motivations et ambitions
            $table->decimal('motivation_ai_score', 5, 2)->nullable()->after('motivation');
            $table->json('motivation_ai_analysis')->nullable()->after('motivation_ai_score');
            
            // Métadonnées d'extraction
            $table->timestamp('ai_extraction_completed_at')->nullable()->after('motivation_ai_analysis');
            $table->string('ai_model_used')->nullable()->after('ai_extraction_completed_at');
            $table->json('ai_extraction_metadata')->nullable()->after('ai_model_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bacheliers', function (Blueprint $table) {
            $table->dropColumn([
                'piece_identite_extracted_data',
                'collante_bac_extracted_data',
                'motivation_ai_score',
                'motivation_ai_analysis',
                'ai_extraction_completed_at',
                'ai_model_used',
                'ai_extraction_metadata'
            ]);
        });
    }
};
