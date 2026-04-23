<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = array_column(Schema::getColumns('users'), 'name');

            if (! in_array('uuid', $columns, true)) {
                $table->uuid('uuid')->nullable()->unique();
            }
            if (! in_array('username', $columns, true)) {
                $table->string('username')->nullable()->unique();
            }
            if (! in_array('phone', $columns, true)) {
                $table->string('phone', 30)->nullable();
            }
            if (! in_array('avatar', $columns, true)) {
                $table->string('avatar', 500)->nullable();
            }
            if (! in_array('country_code', $columns, true)) {
                $table->string('country_code', 5)->nullable();
            }
            if (! in_array('role', $columns, true)) {
                $table->string('role', 20)->default('client');
            }
            if (! in_array('is_active', $columns, true)) {
                $table->boolean('is_active')->default(true);
            }
            if (! in_array('last_seen_at', $columns, true)) {
                $table->timestamp('last_seen_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = array_column(Schema::getColumns('users'), 'name');

            foreach (['uuid', 'username', 'phone', 'avatar', 'country_code', 'role', 'is_active', 'last_seen_at'] as $col) {
                if (in_array($col, $columns, true)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
