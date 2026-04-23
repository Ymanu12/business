<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('freelancer_profiles')) {
            Schema::create('freelancer_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
                $table->string('tagline', 255)->nullable();
                $table->json('skills')->nullable();
                $table->json('languages')->nullable();
                $table->json('education')->nullable();
                $table->json('certifications')->nullable();
                $table->string('portfolio_url', 500)->nullable();
                $table->string('linkedin_url', 500)->nullable();
                $table->string('github_url', 500)->nullable();
                $table->string('twitter_url', 500)->nullable();
                $table->enum('availability', ['available', 'busy', 'unavailable'])->default('available');
                $table->unsignedInteger('response_time')->default(0)->comment('minutes');
                $table->decimal('response_rate', 5, 2)->default(0);
                $table->unsignedInteger('completed_orders')->default(0);
                $table->unsignedInteger('cancelled_orders')->default(0);
                $table->decimal('avg_rating', 3, 2)->default(0);
                $table->unsignedInteger('reviews_count')->default(0);
                $table->decimal('total_earnings', 12, 2)->default(0);
                $table->enum('seller_level', ['new', 'level1', 'level2', 'top_rated'])->default('new');
                $table->boolean('id_verified')->default(false);
                $table->string('id_document', 500)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('freelancer_profiles');
    }
};
