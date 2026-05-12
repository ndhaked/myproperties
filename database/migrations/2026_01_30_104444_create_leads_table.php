<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // --- Contact Details ---
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('mobile');
            $table->string('country')->nullable();
            $table->string('city')->nullable();

            // --- Marketing & Project Info ---
            $table->string('campaign')->nullable();
            
            // Project (Foreign Key)
            $table->foreignId('project_id')
                  ->nullable()
                  ->constrained('projects')
                  ->nullOnDelete();

            // --- Dropdown Relationships (Foreign Keys) ---
            
            // Budget
            $table->string('budget')->nullable();
          
            // Source
            $table->foreignId('source_id')
                  ->nullable()
                  ->constrained('sources')
                  ->nullOnDelete();

            // Medium
            $table->foreignId('medium_id')
                  ->nullable()
                  ->constrained('mediums')
                  ->nullOnDelete();

            // Purpose
            $table->foreignId('purpose_id')
                  ->nullable()
                  ->constrained('purposes')
                  ->nullOnDelete();

            // Purpose Type
            $table->foreignId('purpose_type_id')
                  ->nullable()
                  ->constrained('purpose_types')
                  ->nullOnDelete();

            // Lead Category
            $table->foreignId('lead_category_id')
                  ->nullable()
                  ->constrained('lead_categories')
                  ->nullOnDelete();

            // --- Management Fields ---
            
            // Assigned Agent (Links to Users table)
            // Assumes you are using the standard Laravel 'users' table
            $table->foreignId('assigned_agent_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Status (Default to 'New')
            $table->string('status')->default('new');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};