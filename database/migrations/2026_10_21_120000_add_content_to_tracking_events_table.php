<?php

use App\Support\Tracking\ContentPath;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tracking_events', function (Blueprint $table) {
            // post | research | solution | product | project | audience | page — see ContentPath.
            $table->string('content_type', 20)->nullable()->after('path');
            $table->string('content_slug')->nullable()->after('content_type');

            $table->index(['occurred_on', 'content_type', 'content_slug']);
        });

        DB::table('tracking_events')->select(['id', 'path'])->orderBy('id')->chunkById(1000, function ($rows) {
            foreach ($rows as $row) {
                DB::table('tracking_events')->where('id', $row->id)->update(ContentPath::parse($row->path));
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracking_events', function (Blueprint $table) {
            $table->dropIndex(['occurred_on', 'content_type', 'content_slug']);
            $table->dropColumn(['content_type', 'content_slug']);
        });
    }
};
