<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no')->unique();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // requester
            $table->string('title');
            $table->string('category')->default('other'); // plumbing, electrical, structural, appliance, pest_control, painting, other
            $table->text('description');
            $table->string('priority')->default('medium'); // low, medium, high, emergency
            $table->string('status')->default('submitted'); // submitted, acknowledged, in_progress, completed, cancelled
            $table->string('assigned_to')->nullable();
            $table->date('estimated_completion')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
