<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gig_tag')) {
            Schema::create('gig_tag', function (Blueprint $table) {
                $table->foreignId('gig_id')->constrained()->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
                $table->primary(['gig_id', 'tag_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gig_tag');
    }
};
