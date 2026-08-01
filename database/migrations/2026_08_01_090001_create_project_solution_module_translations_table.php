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
        Schema::create('project_solution_module_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('project_solution_modules')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('technical_note')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();

            $table->unique(['module_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_solution_module_translations');
    }
};
