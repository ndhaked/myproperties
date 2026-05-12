<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_files', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();

            $table->string('file_key', 512)->nullable();
            $table->string('filename', 255);
            $table->string('folder', 255)->nullable();
            $table->string('mime_type', 255)->nullable();

            $table->integer('width')->nullable();
            $table->integer('height')->nullable();

            $table->bigInteger('size_bytes')->nullable();

            $table->boolean('is_primary')->default(false);

            $table->timestamps();
            $table->softDeletes();

            // Optional foreign key (remove if not needed)
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_files');
    }
};
