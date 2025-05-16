<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('podcasts', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('audio_file');
            $table->date('release_date')->nullable()->after('cover_image');
            $table->boolean('is_active')->default(true)->after('release_date');
        });
    }

    public function down()
    {
        Schema::table('podcasts', function (Blueprint $table) {
            $table->dropColumn(['cover_image', 'release_date', 'is_active']);
        });
    }
};
