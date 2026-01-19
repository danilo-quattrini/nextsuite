<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('profile_photo_url')
            ->nullable();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->dateTime('dob');
            $table->string('gender');
            $table->string('nationality');
            $table->foreignIdFor(Company::class, 'company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained('users')
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
