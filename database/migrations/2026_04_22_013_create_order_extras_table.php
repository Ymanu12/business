<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('order_extras')) {
            Schema::create('order_extras', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('gig_extra_id')->constrained('gig_extras')->restrictOnDelete();
                $table->string('title');
                $table->decimal('price', 10, 2);
                $table->unsignedSmallInteger('quantity')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_extras');
    }
};
