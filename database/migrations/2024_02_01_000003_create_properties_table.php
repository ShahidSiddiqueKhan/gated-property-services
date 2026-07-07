<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // owner
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type'); // house, apartment, flat, commercial, office, airbnb, vacation_rental, land
            $table->string('category')->default('residential'); // residential, commercial, airbnb
            $table->string('listing_type')->default('rent'); // rent, sale
            $table->string('status')->default('pending_review'); // occupied, vacant, maintenance, pending_review
            $table->decimal('price', 12, 2)->nullable();
            $table->string('price_period')->nullable(); // month, night, total
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('area_location')->nullable();
            $table->string('size_label')->nullable(); // e.g. 1 Kanal, 1200 Sqft
            $table->unsignedSmallInteger('bedrooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->unsignedInteger('area_sqft')->nullable();
            $table->text('description')->nullable();
            $table->json('amenities')->nullable();
            $table->json('legal_documents')->nullable();
            $table->json('services_requested')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
