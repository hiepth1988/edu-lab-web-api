<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_translations', function (Blueprint $table) {
            // Hide this language version on the public site without deleting it.
            $table->boolean('is_hidden')->default(false)->after('locale');
        });
    }

    public function down(): void
    {
        Schema::table('post_translations', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
        });
    }
};
