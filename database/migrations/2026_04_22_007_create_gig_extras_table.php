<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gig_extras')) {
            Schema::create('gig_extras', function (Blueprint $table) {
                $table->id();
                $table->foreignId('gig_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->string('description', 500)->nullable();
                $table->decimal('price', 10, 2);
                $table->unsignedSmallInteger('delivery_days')->default(0);
                $table->boolean('is_active')->default(true);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gig_extras');
    }
};
