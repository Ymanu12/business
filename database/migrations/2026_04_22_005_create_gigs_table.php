<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gigs')) {
            Schema::create('gigs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('freelancer_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('category_id')->constrained()->restrictOnDelete();
                $table->foreignId('sub_category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('title');
                $table->string('slug', 191)->unique();
                $table->longText('description');
                $table->string('thumbnail', 500)->nullable();
                $table->string('video_url', 500)->nullable();
                $table->decimal('starting_price', 10, 2);
                $table->string('currency', 10)->default('XOF');
                $table->unsignedSmallInteger('delivery_days')->default(3);
                $table->unsignedSmallInteger('revision_count')->default(1);
                $table->enum('status', ['draft', 'pending', 'published', 'rejected', 'paused'])->default('draft');
                $table->text('rejection_reason')->nullable();
                $table->string('seo_title')->nullable();
                $table->string('seo_description', 500)->nullable();
                $table->unsignedInteger('views_count')->default(0);
                $table->unsignedInteger('orders_count')->default(0);
                $table->decimal('avg_rating', 3, 2)->default(0);
                $table->unsignedInteger('reviews_count')->default(0);
                $table->boolean('is_featured')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['status', 'is_featured']);
                $table->index('starting_price');
                $table->index('avg_rating');
                $table->index(['freelancer_id', 'status']);
                $table->index(['category_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gigs');
    }
};
