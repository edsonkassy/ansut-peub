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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bachelier_id')->constrained()->onDelete('cascade');
            $table->foreignId('partenaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('opportunite_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('sujet');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamp('derniere_activite')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
