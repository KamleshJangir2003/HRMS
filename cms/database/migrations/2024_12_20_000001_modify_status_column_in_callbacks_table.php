<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->string('status', 50)->default('call_backs')->change();
        });
    }

    public function down()
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->string('status')->default('call_backs')->change();
        });
    }
};