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
        Schema::create('lead_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained();
            
            $table->string('log_type')->nullable(); // 'call', 'meeting', 'email', 'whatsapp'
            $table->date('log_date')->nullable();
            $table->time('log_time')->nullable();
            $table->text('description')->nullable();
            
            // Specific fields based on type
            $table->string('duration')->nullable(); // For meetings
            $table->string('outcome')->nullable(); // For calls/meetings
            $table->string('status')->nullable(); // If status changed
            $table->string('lead_type')->nullable(); // Hot, Warm, Cold

            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_logs');
    }
};