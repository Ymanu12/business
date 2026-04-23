<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('name', 100);
                $table->string('slug', 120)->unique();
                $table->text('description')->nullable();
                $table->string('icon', 100)->nullable();
                $table->string('color', 20)->nullable();
                $table->string('image', 500)->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->unsignedInteger('gigs_count')->default(0);
                $table->timestamps();

                $table->index(['parent_id', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
