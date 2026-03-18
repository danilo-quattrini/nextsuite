<?php

use App\Models\Field;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('role_field', function (Blueprint $table) {
            $table->foreignIdFor(Role::class)->constrained('roles')->cascadeOnDelete();
            $table->foreignIdFor(Field::class)->constrained('fields')->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['role_id', 'field_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_field');
    }
};
