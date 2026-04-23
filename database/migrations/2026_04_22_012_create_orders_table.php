<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('gig_id')->constrained()->restrictOnDelete();
                $table->foreignId('package_id')->constrained('gig_packages')->restrictOnDelete();
                $table->foreignId('client_id')->constrained('users')->restrictOnDelete();
                $table->foreignId('freelancer_id')->constrained('users')->restrictOnDelete();
                $table->string('title');
                $table->text('requirements')->nullable();
                $table->decimal('price', 10, 2);
                $table->string('currency', 10)->default('XOF');
                $table->decimal('platform_fee', 10, 2)->default(0);
                $table->decimal('freelancer_amount', 10, 2)->default(0);
                $table->unsignedSmallInteger('delivery_days');
                $table->unsignedSmallInteger('revisions_allowed')->default(1);
                $table->unsignedSmallInteger('revisions_used')->default(0);
                $table->enum('status', [
                    'pending', 'paid', 'in_progress', 'delivered',
                    'revision_requested', 'completed', 'cancelled', 'disputed', 'refunded',
                ])->default('pending');
                $table->timestamp('due_date')->nullable();
                $table->timestamp('auto_complete_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->text('cancellation_reason')->nullable();
                $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['status', 'created_at']);
                $table->index(['client_id', 'status']);
                $table->index(['freelancer_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
