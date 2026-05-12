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
        Schema::create('lead_activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Link to the Lead
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            
            // Link to the User who PERFORMED the action (Agent/Admin)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Link to the User who was ASSIGNED (Target Agent) - Nullable
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Type of log: 'call', 'email', 'status_change', 'assignment', 'meeting'
            $table->string('log_type')->default('activity')->index(); 
            
            // Description of the event
            $table->longText('description')->nullable();
            
            // Date and Time of the event
            $table->date('log_date')->nullable();
            $table->time('log_time')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_activity_logs');
    }
};