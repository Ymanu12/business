<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gig_faqs')) {
            Schema::create('gig_faqs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('gig_id')->constrained()->cascadeOnDelete();
                $table->string('question');
                $table->text('answer');
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gig_faqs');
    }
};
