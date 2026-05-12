<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lead_tasks', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained();
           
            // Form Fields
            $table->string('name')->nullable();       // "Name" field
            $table->date('date')->nullable();         // "Date" field
            $table->time('time')->nullable();         // "Time" field
            $table->text('description')->nullable(); // "Description" field
            $table->string('type')->nullable();       // "Type" dropdown (e.g., Call, Email, Meeting)

            // Standard Task Fields (Good practice to add)
            $table->string('status')->default('pending'); // pending, completed, etc.
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_tasks');
    }
};