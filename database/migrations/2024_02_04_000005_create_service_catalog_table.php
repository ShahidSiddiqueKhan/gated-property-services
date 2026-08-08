<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin-configurable standalone/add-on services: advertising packages
     * (listing, photography, drone video, premium marketing) and emergency
     * services (lockout, emergency inspection, night visit). Prices support
     * an optional range (price to price_max) for display purposes.
     */
    public function up(): void
    {
        Schema::create('service_catalog', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // advertising | emergency
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('price_max', 12, 2)->nullable(); // null = fixed price, set = a "from X to Y" range
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_catalog');
    }
};
