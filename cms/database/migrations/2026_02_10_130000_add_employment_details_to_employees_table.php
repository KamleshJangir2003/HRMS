<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('joining_date')->nullable()->after('is_approved');
            $table->decimal('current_ctc', 10, 2)->nullable()->after('joining_date');
            $table->decimal('in_hand_salary', 10, 2)->nullable()->after('current_ctc');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['joining_date', 'current_ctc', 'in_hand_salary']);
        });
    }
};