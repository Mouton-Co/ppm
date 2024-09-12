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
        Schema::table('parts', function (Blueprint $table) {
            $table->boolean('treatment_1_part_dispatched')->default(false);
            $table->datetime('treatment_1_part_dispatched_at')->nullable();
            $table->boolean('treatment_2_part_dispatched')->default(false);
            $table->datetime('treatment_2_part_dispatched_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn('treatment_1_part_dispatched');
            $table->dropColumn('treatment_1_part_dispatched_at');
            $table->dropColumn('treatment_2_part_dispatched');
            $table->dropColumn('treatment_2_part_dispatched_at');
        });
    }
};
