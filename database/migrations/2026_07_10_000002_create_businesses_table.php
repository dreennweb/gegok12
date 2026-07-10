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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('legal_name');
            $table->enum('business_type', ['company', 'aop', 'sole_proprietorship']);
            $table->string('ntn')->nullable()->unique();
            $table->string('cnic')->nullable();
            $table->string('registration_number')->nullable();
            $table->text('business_description')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->json('business_nature')->nullable();
            $table->json('selected_departments')->nullable();
            $table->boolean('has_workers')->default(false);
            $table->integer('worker_count')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('ntn');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
