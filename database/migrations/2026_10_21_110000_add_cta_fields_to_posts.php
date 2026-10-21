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
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('show_cta')->default(true)->after('is_featured');
        });

        Schema::table('post_translations', function (Blueprint $table) {
            $table->text('cta_text')->nullable()->after('content');
            $table->string('cta_label')->nullable()->after('cta_text');
            $table->string('cta_url', 2048)->nullable()->after('cta_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_translations', function (Blueprint $table) {
            $table->dropColumn(['cta_text', 'cta_label', 'cta_url']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('show_cta');
        });
    }
};
