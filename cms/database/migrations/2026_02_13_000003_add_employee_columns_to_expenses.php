<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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