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
        // Filled by `php artisan tracking:sync-ip-ranges`. Addresses are stored as fixed-width
        // lowercase hex (8 chars for IPv4, 32 for IPv6) so plain string comparison orders them.
        Schema::create('ip_country_ranges', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('ip_version');
            $table->string('range_start', 32);
            $table->string('range_end', 32);
            $table->char('country', 2);

            $table->index(['ip_version', 'range_start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_country_ranges');
    }
};
