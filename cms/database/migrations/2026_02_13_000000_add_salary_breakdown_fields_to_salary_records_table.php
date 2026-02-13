<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_records', function (Blueprint $table) {
            $table->decimal('advance', 10, 2)->default(0)->after('deduction');
            $table->decimal('incentive', 10, 2)->default(0)->after('advance');
            $table->decimal('employee_pf', 10, 2)->default(0)->after('incentive');
            $table->decimal('employee_esi', 10, 2)->default(0)->after('employee_pf');
            $table->decimal('employer_pf', 10, 2)->default(0)->after('employee_esi');
            $table->decimal('employer_esi', 10, 2)->default(0)->after('employer_pf');
        });
    }

    public function down(): void
    {
        Schema::table('salary_records', function (Blueprint $table) {
            $table->dropColumn(['advance', 'incentive', 'employee_pf', 'employee_esi', 'employer_pf', 'employer_esi']);
        });
    }
};