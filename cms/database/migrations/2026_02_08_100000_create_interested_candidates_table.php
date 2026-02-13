<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interested_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('role');
            $table->string('resume')->nullable();
            $table->enum('status', ['interested', 'interview_scheduled', 'selected', 'rejected'])->default('interested');
            $table->text('notes')->nullable();
            $table->timestamp('interested_at')->useCurrent();
            $table->timestamps();
            
            $table->unique('number');
            $table->index(['status', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('interested_candidates');
    }
};