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
        Schema::create('timezones', function (Blueprint $table) {
            $table->id();
            $table->string('timezone', 100)->unique()->comment('Timezone identifier (e.g., UTC, America/New_York)');
            $table->string('name', 150)->comment('Display name (e.g., Eastern Standard Time)');
            $table->string('abbreviation', 10)->nullable()->comment('Timezone abbreviation (e.g., EST, PST)');
            $table->string('offset', 10)->comment('UTC offset (e.g., UTC-05:00, UTC+05:30)');
            $table->integer('offset_seconds')->comment('Offset in seconds from UTC');
            $table->boolean('is_active')->default(true)->comment('Whether this timezone is active/available');
            $table->integer('sort_order')->default(0)->comment('Sort order for display');
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timezones');
    }
};
