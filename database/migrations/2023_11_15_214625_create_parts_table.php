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
            $table->string('status')->default('processing');
            $table->boolean('part_ordered')->default(false);
            $table->datetime('part_ordered_at')->nullable();
            $table->boolean('raw_part_received')->default(false);
            $table->datetime('raw_part_received_at')->nullable();
            $table->boolean('treatment_1_part_received')->default(false);
            $table->datetime('treatment_1_part_received_at')->nullable();
            $table->boolean('treatment_2_part_received')->default(false);
            $table->datetime('treatment_2_part_received_at')->nullable();
            $table->boolean('completed_part_received')->default(false);
            $table->datetime('completed_part_received_at')->nullable();
            $table->foreignId('submission_id');
            $table->foreignId('supplier_id')->nullable();
            $table->boolean('qc_passed')->default(false);
            $table->datetime('qc_passed_at')->nullable();
            $table->boolean('qc_issue')->default(false);
            $table->datetime('qc_issue_at')->nullable();
            $table->string('qc_issue_reason')->nullable();
            $table->string('treatment_1')->nullable();
            $table->string('treatment_1_supplier')->nullable();
            $table->string('treatment_2')->nullable();
            $table->string('treatment_2_supplier')->nullable();
            $table->text('comment_procurement')->nullable();
            $table->text('comment_warehouse')->nullable();
            $table->text('comment_logistics')->nullable();
            $table->integer('quantity_in_stock')->nullable();
            $table->integer('quantity_ordered')->nullable();
            $table->integer('qty_received')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
