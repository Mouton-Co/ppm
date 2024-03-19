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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('machine_nr')->nullable();
            $table->string('country')->nullable();
            $table->string('coc')->nullable();
            $table->text('noticed_issue')->nullable();
            $table->text('proposed_solution')->nullable();
            $table->string('currently_responsible')->nullable();
            $table->string('status')->nullable();
            $table->string('resolved_at')->nullable();
            $table->string('related_po')->nullable();
            $table->text('customer_comment')->nullable();
            $table->text('commisioner_comment')->nullable();
            $table->text('logistics_comment')->nullable();
            $table->foreignId('submission_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
