<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing opportunities from 'article' to 'promotion'
        DB::table('opportunites')
            ->where('type', 'article')
            ->update(['type' => 'promotion']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert promotions back to articles
        DB::table('opportunites')
            ->where('type', 'promotion')
            ->update(['type' => 'article']);
    }
};
