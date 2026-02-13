<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update the status enum to include all required statuses
        DB::statement("ALTER TABLE attendance MODIFY COLUMN status ENUM('Present', 'Absent', 'Half Day', 'Unauthorized Leave', 'Paid Leave', 'Holiday', 'Week Off', 'Comp Off') DEFAULT 'Present'");
        
        // Add shift column if it doesn't exist
        Schema::table('attendance', function (Blueprint $table) {
            if (!Schema::hasColumn('attendance', 'shift')) {
                $table->enum('shift', ['Day', 'Night'])->default('Day')->after('status');
            }
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE attendance MODIFY COLUMN status ENUM('Present', 'Absent', 'Leave', 'Half Day') DEFAULT 'Present'");
    }
};