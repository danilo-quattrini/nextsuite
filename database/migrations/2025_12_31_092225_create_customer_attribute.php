<?php

use App\Models\Attribute;
use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_attribute', function (Blueprint $table) {
            $table->foreignIdFor(Customer::class)->constrained('customers')->cascadeOnDelete();
            $table->foreignIdFor(Attribute::class)->constrained('attributes')->cascadeOnDelete();
            $table->json('value')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_attribute');
    }
};
