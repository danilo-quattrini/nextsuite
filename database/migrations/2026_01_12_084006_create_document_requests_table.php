<?php

use App\Models\Customer;
use App\Models\Document;
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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('type');

            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])
                ->default('pending');

            $table->string('document_url')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->nullableMorphs('requested_by');

            $table->foreignIdFor(Document::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
