<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('withdrawals')) {
            Schema::create('withdrawals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->decimal('amount', 10, 2);
                $table->string('currency', 10)->default('XOF');
                $table->enum('method', ['mtn_momo', 'orange_money', 'flooz', 'tmoney', 'moov_money', 'bank', 'paypal']);
                $table->json('account_details');
                $table->enum('status', ['pending', 'approved', 'processed', 'rejected'])->default('pending');
                $table->string('transaction_ref', 191)->nullable();
                $table->text('rejection_reason')->nullable();
                $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index(['status', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
