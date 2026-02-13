<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->enum('shift', ['Day', 'Night']);
            $table->decimal('salary', 10, 2);
            $table->string('job_timing');
            $table->integer('estimated_time_to_hire');
            $table->text('job_description');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_openings');
    }
};