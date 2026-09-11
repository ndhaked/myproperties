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
        Schema::table('property_images', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->change();
            $table->foreignId('owner_id')->nullable()->after('property_id')->constrained('users')->nullOnDelete();
        });

        Schema::table('property_documents', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->change();
            $table->foreignId('owner_id')->nullable()->after('property_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_images', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner_id');
            $table->foreignId('property_id')->nullable(false)->change();
        });

        Schema::table('property_documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner_id');
            $table->foreignId('property_id')->nullable(false)->change();
        });
    }
};
