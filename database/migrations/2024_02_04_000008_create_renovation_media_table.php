<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('renovation_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('renovation_project_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('photo'); // photo | video | invoice
            $table->string('phase')->nullable(); // before | progress | after — null for invoices
            $table->string('path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('renovation_media');
    }
};
