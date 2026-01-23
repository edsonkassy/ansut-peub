<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bachelier_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('initiator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('users')->onDelete('cascade');
            $table->string('subject')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->boolean('initiator_archived')->default(false);
            $table->boolean('participant_archived')->default(false);
            $table->timestamps();

            // Éviter les doublons (une conversation entre A et B = conversation entre B et A)
            $table->unique(['initiator_id', 'participant_id']);
            $table->index(['initiator_id', 'last_message_at']);
            $table->index(['participant_id', 'last_message_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bachelier_conversations');
    }
};