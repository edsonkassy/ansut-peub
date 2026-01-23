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
        Schema::table('dotations_fournisseurs', function (Blueprint $table) {
            $table->string('contrat_url')->nullable()->after('contact_telephone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dotations_fournisseurs', function (Blueprint $table) {
            $table->dropColumn('contrat_url');
        });
    }
};
