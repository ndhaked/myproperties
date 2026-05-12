<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mediums', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'organic', 'social'
            $table->boolean('status')->default(1); // Default to Active
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mediums');
    }
};