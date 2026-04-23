<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gig_requirements')) {
            Schema::create('gig_requirements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('gig_id')->constrained()->cascadeOnDelete();
                $table->string('question');
                $table->enum('type', ['text', 'file', 'multiple_choice'])->default('text');
                $table->json('options')->nullable();
                $table->boolean('is_required')->default(true);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gig_requirements');
    }
};
