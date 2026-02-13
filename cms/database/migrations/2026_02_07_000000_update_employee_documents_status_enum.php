<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, update any existing 'pending' status to 'uploaded' for documents that have files
        DB::table('employee_documents')
            ->where('status', 'pending')
            ->whereNotNull('file_path')
            ->update(['status' => 'uploaded']);

        // Modify the enum to include 'submitted' status
        DB::statement("ALTER TABLE employee_documents MODIFY COLUMN status ENUM('pending', 'uploaded', 'submitted', 'verified', 'expired', 'rejected') DEFAULT 'pending'");
    }

    public function down()
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE employee_documents MODIFY COLUMN status ENUM('pending', 'uploaded', 'verified', 'expired', 'rejected') DEFAULT 'pending'");
        
        // Update any 'submitted' status back to 'pending'
        DB::table('employee_documents')
            ->where('status', 'submitted')
            ->update(['status' => 'pending']);
    }
};