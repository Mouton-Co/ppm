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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('submission_code')->unique(); // username-timestamp-user_id
            $table->string('assembly_name')->nullable();
            $table->string('machine_number')->nullable();
            $table->string('submission_type')->nullable();
            $table->string('current_unit_number')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('submitted')->default(0);
            $table->foreignId('user_id');
            $table->foreignId('project_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
