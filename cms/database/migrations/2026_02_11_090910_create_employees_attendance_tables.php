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
        // Create employees table if it doesn't exist
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('employee_code', 20)->unique();
                $table->string('name', 100);
                $table->string('department', 50);
                $table->string('job_title', 100);
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        // Create attendance table
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['Present', 'Absent', 'Leave', 'Half Day'])->default('Present');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'attendance_date']);
        });

        // Insert sample employees
        DB::table('employees')->insert([
            ['employee_code' => 'EMP001', 'name' => 'John Doe', 'department' => 'IT', 'job_title' => 'Software Developer', 'created_at' => now(), 'updated_at' => now()],
            ['employee_code' => 'EMP002', 'name' => 'Jane Smith', 'department' => 'HR', 'job_title' => 'HR Manager', 'created_at' => now(), 'updated_at' => now()],
            ['employee_code' => 'EMP003', 'name' => 'Mike Johnson', 'department' => 'Finance', 'job_title' => 'Accountant', 'created_at' => now(), 'updated_at' => now()],
            ['employee_code' => 'EMP004', 'name' => 'Sarah Wilson', 'department' => 'IT', 'job_title' => 'System Admin', 'created_at' => now(), 'updated_at' => now()],
            ['employee_code' => 'EMP005', 'name' => 'David Brown', 'department' => 'Marketing', 'job_title' => 'Marketing Executive', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('employees');
    }
};
