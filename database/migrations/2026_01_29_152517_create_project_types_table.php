<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Residential Leasing'
            $table->string('slug')->nullable(); // e.g., 'residential-leasing'
            $table->boolean('status')->default(1); // Active by default
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_types');
    }
};