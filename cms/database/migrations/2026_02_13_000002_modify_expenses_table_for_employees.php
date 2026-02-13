<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, let's check if the table has the old structure and modify it
        if (Schema::hasTable('expenses')) {
            // Add new columns if they don't exist
            Schema::table('expenses', function (Blueprint $table) {
                if (!Schema::hasColumn('expenses', 'employee_id')) {
                    $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('cascade');
                }
                if (!Schema::hasColumn('expenses', 'status')) {
                    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                }
                if (!Schema::hasColumn('expenses', 'admin_notes')) {
                    $table->text('admin_notes')->nullable();
                }
                if (!Schema::hasColumn('expenses', 'receipt_path')) {
                    $table->string('receipt_path')->nullable();
                }
            });

            // Update existing enum values if needed
            DB::statement("ALTER TABLE expenses MODIFY COLUMN payment_method ENUM('PhonePe', 'UPI', 'Scanner', 'Others', 'Bank Transfer', 'Cash', 'Card')");
            DB::statement("ALTER TABLE expenses MODIFY COLUMN category ENUM('Birthday', 'Office Supplies', 'Travel', 'Food', 'Others')");
        }
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (Schema::hasColumn('expenses', 'employee_id')) {
                $table->dropForeign(['employee_id']);
                $table->dropColumn('employee_id');
            }
            if (Schema::hasColumn('expenses', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('expenses', 'admin_notes')) {
                $table->dropColumn('admin_notes');
            }
            if (Schema::hasColumn('expenses', 'receipt_path')) {
                $table->dropColumn('receipt_path');
            }
        });
    }
};