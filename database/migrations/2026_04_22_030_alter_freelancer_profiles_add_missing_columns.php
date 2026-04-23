<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('freelancer_profiles', function (Blueprint $table) {
            $cols = array_column(Schema::getColumns('freelancer_profiles'), 'name');

            if (!in_array('tagline', $cols)) {
                $table->string('tagline', 255)->nullable()->after('user_id');
            }
            if (!in_array('education', $cols)) {
                $table->json('education')->nullable()->after('languages');
            }
            if (!in_array('certifications', $cols)) {
                $table->json('certifications')->nullable()->after('education');
            }
            if (!in_array('portfolio_url', $cols)) {
                $table->string('portfolio_url', 500)->nullable();
            }
            if (!in_array('linkedin_url', $cols)) {
                $table->string('linkedin_url', 500)->nullable();
            }
            if (!in_array('github_url', $cols)) {
                $table->string('github_url', 500)->nullable();
            }
            if (!in_array('twitter_url', $cols)) {
                $table->string('twitter_url', 500)->nullable();
            }
            if (!in_array('availability', $cols)) {
                $table->enum('availability', ['available', 'busy', 'unavailable'])->default('available');
            }
            if (!in_array('response_time', $cols)) {
                $table->unsignedInteger('response_time')->default(0)->comment('minutes');
            }
            if (!in_array('response_rate', $cols)) {
                $table->decimal('response_rate', 5, 2)->default(0);
            }
            if (!in_array('cancelled_orders', $cols)) {
                $table->unsignedInteger('cancelled_orders')->default(0);
            }
            if (!in_array('avg_rating', $cols)) {
                $table->decimal('avg_rating', 3, 2)->default(0);
            }
            if (!in_array('reviews_count', $cols)) {
                $table->unsignedInteger('reviews_count')->default(0);
            }
            if (!in_array('total_earnings', $cols)) {
                $table->decimal('total_earnings', 12, 2)->default(0);
            }
            if (!in_array('seller_level', $cols)) {
                $table->enum('seller_level', ['new', 'level1', 'level2', 'top_rated'])->default('new');
            }
            if (!in_array('id_verified', $cols)) {
                $table->boolean('id_verified')->default(false);
            }
            if (!in_array('id_document', $cols)) {
                $table->string('id_document', 500)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('freelancer_profiles', function (Blueprint $table) {
            $cols = ['tagline', 'education', 'certifications', 'portfolio_url', 'linkedin_url',
                     'github_url', 'twitter_url', 'availability', 'response_time', 'response_rate',
                     'cancelled_orders', 'avg_rating', 'reviews_count', 'total_earnings',
                     'seller_level', 'id_verified', 'id_document'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('freelancer_profiles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
