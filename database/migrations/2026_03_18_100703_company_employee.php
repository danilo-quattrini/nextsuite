<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_employee', function (Blueprint $table) {
            $table->foreignIdFor(Company::class)
                ->constrained('companies')
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'employee_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['company_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_employee');
    }
};
