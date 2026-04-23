<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
                $table->foreignId('gig_id')->constrained()->cascadeOnDelete();
                $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('reviewed_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->unsignedTinyInteger('communication_rating');
                $table->unsignedTinyInteger('service_rating');
                $table->boolean('recommend')->default(true);
                $table->text('comment');
                $table->text('reply')->nullable();
                $table->timestamp('reply_at')->nullable();
                $table->boolean('is_verified')->default(true);
                $table->boolean('is_hidden')->default(false);
                $table->timestamps();

                $table->index(['gig_id', 'is_hidden']);
                $table->index(['reviewed_id', 'is_hidden']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
