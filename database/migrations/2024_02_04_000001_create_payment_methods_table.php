<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "JazzCash", "Wise", "Raast QR"
            $table->string('code')->unique(); // e.g. jazzcash, wise, raast — 'gateway' types map to real checkout logic by this code
            $table->string('type')->default('manual'); // gateway (live checkout) | manual (instructions, client self-confirms)
            $table->string('region')->default('both'); // local | overseas | both
            $table->string('icon')->nullable(); // icon name from the x-icon component set
            $table->text('instructions')->nullable(); // shown to clients for manual methods (account details, steps, etc.)
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
