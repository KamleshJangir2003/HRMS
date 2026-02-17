<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('intern_callbacks', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('name');
            $table->string('role');
            $table->string('platform')->nullable();
            $table->date('callback_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('intern_callbacks');
    }
};