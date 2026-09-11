<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique()->after('name');
            $table->string('country_code')->default('+91')->after('phone');
            $table->string('role')->nullable()->after('country_code');
            $table->string('avatar')->nullable()->after('role');
            $table->boolean('is_verified')->default(false)->after('avatar');
            $table->boolean('notifications_enabled')->default(true)->after('is_verified');

            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'country_code',
                'role',
                'avatar',
                'is_verified',
                'notifications_enabled',
            ]);
        });
    }
};
