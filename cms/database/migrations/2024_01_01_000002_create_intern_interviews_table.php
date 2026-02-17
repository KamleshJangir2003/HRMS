<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('intern_interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intern_id');
            $table->unsignedBigInteger('interviewer_id');
            $table->date('interview_date');
            $table->time('interview_time');
            $table->enum('interview_type', ['online', 'offline', 'phone'])->default('online');
            $table->string('meeting_link')->nullable();
            $table->enum('status', ['Scheduled', 'Completed', 'Cancelled'])->default('Scheduled');
            $table->enum('result', ['Selected', 'Rejected', 'Pending'])->default('Pending');
            $table->text('feedback')->nullable();
            $table->integer('technical_score')->nullable();
            $table->integer('communication_score')->nullable();
            $table->integer('overall_score')->nullable();
            $table->timestamps();

            $table->foreign('intern_id')->references('id')->on('interns')->onDelete('cascade');
            $table->foreign('interviewer_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('intern_interviews');
    }
};