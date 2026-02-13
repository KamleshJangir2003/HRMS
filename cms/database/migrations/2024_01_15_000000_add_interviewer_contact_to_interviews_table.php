<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->string('interviewer_email')->nullable()->after('interviewer');
            $table->string('interviewer_phone')->nullable()->after('interviewer_email');
        });
    }

    public function down()
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn(['interviewer_email', 'interviewer_phone']);
        });
    }
};