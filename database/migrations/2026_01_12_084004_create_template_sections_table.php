<?php

use App\Models\Template;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('template_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->foreignIdFor(Template::class)->constrained('templates');
            $table->string('data_source');
            $table->json('formatting_rules');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_sections');
    }
};
