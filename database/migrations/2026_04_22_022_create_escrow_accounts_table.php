<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('escrow_accounts')) {
            Schema::create('escrow_accounts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
                $table->decimal('amount', 10, 2);
                $table->decimal('platform_fee', 10, 2);
                $table->decimal('freelancer_amount', 10, 2);
                $table->enum('status', ['held', 'released', 'refunded', 'disputed'])->default('held');
                $table->timestamp('held_at')->useCurrent();
                $table->timestamp('released_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('escrow_accounts');
    }
};
