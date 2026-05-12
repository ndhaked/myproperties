<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained(); // The user who added the note

            // Note Content
            $table->text('description')->nullable(); // The main note content (from your WYSIWYG editor)

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_notes');
    }
};