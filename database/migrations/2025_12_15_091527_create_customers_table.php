<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('profile_photo_url');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->dateTime('dob');
            $table->string('gender');
            $table->foreignIdFor(Company::class, 'company_id')
                ->constrained('companies')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
