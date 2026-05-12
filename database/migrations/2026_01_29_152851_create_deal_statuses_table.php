<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deal_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Commission Released'
            $table->string('slug')->nullable(); // e.g., 'commission-released'
            $table->boolean('status')->default(1); // Active by default
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deal_statuses');
    }
};