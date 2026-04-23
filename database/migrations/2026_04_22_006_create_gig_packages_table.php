<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gig_packages')) {
            Schema::create('gig_packages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('gig_id')->constrained()->cascadeOnDelete();
                $table->enum('type', ['basic', 'standard', 'premium']);
                $table->string('name', 100);
                $table->text('description');
                $table->decimal('price', 10, 2);
                $table->unsignedSmallInteger('delivery_days');
                $table->unsignedSmallInteger('revision_count')->default(1);
                $table->json('features')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['gig_id', 'type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gig_packages');
    }
};
