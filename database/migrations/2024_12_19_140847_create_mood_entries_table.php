<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mood_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Ensure this is present
            $table->string('mood_description');
            $table->integer('mood_intensity');
            $table->string('emotional_state');
            $table->text('trigger_description')->nullable();
            $table->integer('sleep_quality');
            $table->text('activities_description')->nullable();
            $table->text('gratitude_entry')->nullable();
            $table->text('goal_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_entries');
    }
};