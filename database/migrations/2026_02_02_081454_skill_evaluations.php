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
        Schema::create('skill_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users');
            $table->integer('level');
            $table->text('notes')->nullable();
            $table->timestamp('evaluated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_evaluations');
    }
};
