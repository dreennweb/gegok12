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
        Schema::create('department_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_review', 'queried', 'approved', 'rejected'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('existing_registration_number')->nullable();
            $table->text('query_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('certificate_number')->nullable();
            $table->timestamp('certificate_issued_at')->nullable();
            $table->json('department_specific_data')->nullable();
            $table->integer('progress')->default(0);
            $table->timestamps();
            $table->index(['application_id', 'department_id']);
            $table->index('status');
            $table->index('assigned_to');
            $table->unique(['application_id', 'department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_applications');
    }
};
