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
        // Create attendance table
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('attendance_date');
            $table->enum('status', ['Present', 'Absent', 'Leave', 'Half Day'])->default('Present');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'attendance_date']);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
