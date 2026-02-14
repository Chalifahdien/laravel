<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('template_frames', function (Blueprint $table) {
            $table->longText('path_data')->nullable(); // Store JSON points or SVG path data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_frames', function (Blueprint $table) {
            $table->dropColumn('path_data');
        });
    }
};
