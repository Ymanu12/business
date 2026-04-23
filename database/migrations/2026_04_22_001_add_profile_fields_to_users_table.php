<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = array_column(Schema::getColumns('users'), 'name');

            if (! in_array('phone_verified_at', $cols)) {
                $table->timestamp('phone_verified_at')->nullable();
            }
            if (! in_array('banner', $cols)) {
                $table->string('banner', 500)->nullable();
            }
            if (! in_array('bio', $cols)) {
                $table->text('bio')->nullable();
            }
            if (! in_array('city', $cols)) {
                $table->string('city', 100)->nullable();
            }
            if (! in_array('is_verified', $cols)) {
                $table->boolean('is_verified')->default(false);
            }
            if (! in_array('deleted_at', $cols)) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = array_column(Schema::getColumns('users'), 'name');

            foreach (['phone_verified_at', 'banner', 'bio', 'city', 'is_verified', 'deleted_at'] as $col) {
                if (in_array($col, $cols)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
