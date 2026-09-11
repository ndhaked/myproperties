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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('property_type_id')->constrained('property_types')->restrictOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('location_line')->nullable();
            $table->foreignId('city_id')->constrained('cities')->restrictOnDelete();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            $table->decimal('price_amount', 14, 2);
            $table->decimal('price_per_sqft', 12, 2)->nullable();

            $table->unsignedInteger('plot_area_min')->nullable();
            $table->unsignedInteger('plot_area_max')->nullable();
            $table->string('plot_area_unit')->default('sq.ft');
            $table->string('plot_size')->nullable();

            $table->string('facing')->nullable();
            $table->boolean('is_corner_plot')->default(false);
            $table->string('property_age')->nullable();
            $table->date('possession_date')->nullable();
            $table->string('road_width')->nullable();

            $table->text('description')->nullable();

            $table->string('badge_label')->nullable();
            $table->string('badge_color')->nullable();
            $table->string('offer_text')->nullable();
            $table->string('offer_highlight')->nullable();
            $table->string('offer_bg_color')->nullable();
            $table->string('offer_text_color')->nullable();

            $table->string('status')->default('Active');
            $table->unsignedInteger('views')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
