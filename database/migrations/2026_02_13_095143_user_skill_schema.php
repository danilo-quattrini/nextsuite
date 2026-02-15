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
        Schema::create('skill_schemas', function (Blueprint $table) {
            $table->id();
            $table->morphs('assignable'); // assignable_type, assignable_id (Customer, etc.)
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->integer('default_level')->default(30);
            $table->timestamps();

            $table->unique(['assignable_type', 'assignable_id', 'skill_id']);

        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_schemas');
    }
};
