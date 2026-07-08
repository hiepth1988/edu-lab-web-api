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
        Schema::create('solution_faq_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solution_faq_id')->constrained('solution_faqs')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('question');
            $table->text('answer')->nullable();
            $table->timestamps();

            $table->unique(['solution_faq_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solution_faq_translations');
    }
};
