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
        Schema::table('solution_translations', function (Blueprint $table) {
            $table->text('architecture_approach')->nullable()->after('architecture_note');
            $table->json('trust_safety')->nullable()->after('use_cases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solution_translations', function (Blueprint $table) {
            $table->dropColumn(['architecture_approach', 'trust_safety']);
        });
    }
};
