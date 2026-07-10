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
        Schema::create('application_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_application_id')->constrained('department_applications')->onDelete('cascade');
            $table->foreignId('queried_by')->constrained('users');
            $table->text('query_description');
            $table->json('required_documents')->nullable();
            $table->enum('status', ['open', 'responded', 'resolved'])->default('open');
            $table->timestamp('responded_at')->nullable();
            $table->text('response_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->index('department_application_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_queries');
    }
};
