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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('material')->nullable();
            $table->string('material_thickness')->nullable();
            $table->string('finish')->nullable();
            $table->string('used_in_weldment')->nullable();
            $table->string('process_type')->nullable();
            $table->string('manufactured_or_purchased')->nullable();
            $table->text('notes')->nullable();
            $table->string('po_number')->nullable();
            $table->date('date_stamp')->nullable();
            $table->string('status')->nullable();
            $table->foreignId('submission_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
