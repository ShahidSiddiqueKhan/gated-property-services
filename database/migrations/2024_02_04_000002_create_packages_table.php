<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Premium, Full Valet, or a custom package
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('features')->nullable(); // list of bullet points shown to clients
            $table->decimal('monthly_price', 12, 2)->default(0); // standard monthly package fee
            $table->decimal('rent_commission_percent', 5, 2)->default(0); // default % of monthly rent GATED keeps
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
