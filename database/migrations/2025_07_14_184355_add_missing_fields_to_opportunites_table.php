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
        Schema::table('opportunites', function (Blueprint $table) {
            $table->string('niveau_etude_requis')->nullable()->after('nombre_places');
            $table->json('series_acceptees')->nullable()->after('niveau_etude_requis');
            $table->decimal('moyenne_minimum', 5, 2)->nullable()->after('series_acceptees');
            $table->json('regions_ciblees')->nullable()->after('moyenne_minimum');
            $table->string('contact_email')->nullable()->after('documents_requis');
            $table->string('contact_telephone')->nullable()->after('contact_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunites', function (Blueprint $table) {
            $table->dropColumn([
                'niveau_etude_requis',
                'series_acceptees', 
                'moyenne_minimum',
                'regions_ciblees',
                'contact_email',
                'contact_telephone'
            ]);
        });
    }
};
