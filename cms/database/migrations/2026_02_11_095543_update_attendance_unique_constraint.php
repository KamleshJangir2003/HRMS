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
        // Add new unique constraint with shift (without dropping old one)
        Schema::table('attendance', function (Blueprint $table) {
            $table->unique(['employee_id', 'attendance_date', 'shift'], 'attendance_unique_with_shift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropIndex('attendance_unique_with_shift');
            $table->unique(['employee_id', 'attendance_date']);
        });
    }
};
