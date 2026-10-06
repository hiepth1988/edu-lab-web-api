<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// "Khoa học phía sau" section (docs/edulab_topthi_page_update.md, mục 3) + a caption
// under the journey steps for journeys that loop back (the Product Journey component
// only renders a straight list).
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_translations', function (Blueprint $table) {
            $table->text('journey_note')->nullable()->after('journey_steps');
            $table->string('science_heading')->nullable()->after('journey_note');
            $table->text('science_description')->nullable()->after('science_heading');
            $table->json('science_cards')->nullable()->after('science_description');
            $table->text('science_note')->nullable()->after('science_cards');
        });
    }

    public function down(): void
    {
        Schema::table('project_translations', function (Blueprint $table) {
            $table->dropColumn(['journey_note', 'science_heading', 'science_description', 'science_cards', 'science_note']);
        });
    }
};
