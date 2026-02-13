<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'basic_salary')) {
                $table->decimal('basic_salary', 10, 2)->nullable()->after('in_hand_salary');
            }
            if (!Schema::hasColumn('employees', 'job_title')) {
                $table->string('job_title')->nullable()->after('department');
            }
            if (!Schema::hasColumn('employees', 'shift')) {
                $table->enum('shift', ['Day', 'Night'])->default('Day')->after('job_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['basic_salary', 'job_title', 'shift']);
        });
    }
};