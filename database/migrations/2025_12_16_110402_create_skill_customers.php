<?php

use App\Models\Customer;
use App\Models\Skill;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skill_customers', function (Blueprint $table) {
            $table->id();
            $table->integer('level')->default(0);
            $table->integer('years')->nullable();
            $table->string('notes')->nullable();
            $table->foreignIdFor(Skill::class)->constrained('skills');
            $table->foreignIdFor(Customer::class)->constrained('customers');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_customers');
    }
};
