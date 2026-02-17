<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->string('course')->nullable()->after('mentor_id');
            $table->unsignedBigInteger('hr_id')->nullable()->after('course');
            $table->text('profile_details')->nullable()->after('hr_id');
            $table->text('notes')->nullable()->after('profile_details');
            $table->json('documents')->nullable()->after('notes');
            
            $table->foreign('hr_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->dropForeign(['hr_id']);
            $table->dropColumn(['course', 'hr_id', 'profile_details', 'notes', 'documents']);
        });
    }
};