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
        Schema::create('research_topic_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_topic_id')->constrained('research_topics')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('slug');
            $table->string('name');
            $table->timestamps();

            $table->unique(['research_topic_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_topic_translations');
    }
};
