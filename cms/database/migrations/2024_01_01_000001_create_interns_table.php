<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interns', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('role')->default('Intern');
            $table->string('platform')->nullable();
            $table->string('resume')->nullable();
            $table->string('status')->default('new');
            $table->string('condition_status')->nullable();
            $table->text('reason')->nullable();
            $table->string('final_result')->default('Pending');
            $table->text('rejection_reason')->nullable();
            $table->integer('internship_duration')->nullable(); // in months
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('stipend', 8, 2)->nullable();
            $table->unsignedBigInteger('mentor_id')->nullable();
            $table->timestamps();

            $table->foreign('mentor_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('interns');
    }
};