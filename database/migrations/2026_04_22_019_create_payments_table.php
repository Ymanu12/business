<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->restrictOnDelete();
                $table->foreignId('user_id')->constrained()->restrictOnDelete();
                $table->decimal('amount', 10, 2);
                $table->string('currency', 10)->default('XOF');
                $table->enum('method', [
                    'stripe', 'paypal', 'mtn_momo', 'orange_money',
                    'flooz', 'tmoney', 'moov_money', 'wallet',
                ]);
                $table->string('provider', 100)->nullable();
                $table->string('provider_id', 191)->nullable();
                $table->string('provider_ref', 191)->nullable();
                $table->enum('status', ['pending', 'processing', 'paid', 'failed', 'refunded'])->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['order_id', 'status']);
                $table->index('provider_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
