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
        Schema::create('tracking_events', function (Blueprint $table) {
            $table->id();
            $table->string('event', 50);
            $table->string('label')->nullable();
            $table->string('path', 512);
            $table->string('target', 1024)->nullable();
            $table->integer('value')->nullable();
            $table->json('meta')->nullable();

            $table->uuid('visitor_id');
            $table->uuid('session_id');
            $table->string('locale', 5)->nullable();

            // vn | foreign | unknown — see App\Support\Tracking\VisitorClassifier.
            $table->string('visitor_type', 10);
            $table->char('country', 2)->nullable();
            // cloudflare | ip | timezone | language — which signal decided visitor_type.
            $table->string('geo_source', 12)->nullable();
            $table->string('timezone', 64)->nullable();
            $table->string('browser_language', 20)->nullable();

            $table->string('referrer_host')->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('device', 10)->nullable();
            // Salted hash only — raw IPs are never stored.
            $table->char('ip_hash', 64)->nullable();

            // Calendar day in config('tracking.timezone'), so reports split days the way the team reads them.
            $table->date('occurred_on');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['occurred_on', 'event']);
            $table->index(['occurred_on', 'visitor_type']);
            $table->index('visitor_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_events');
    }
};
