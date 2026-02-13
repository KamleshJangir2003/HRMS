<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Personal Details
            $table->string('father_name')->nullable()->after('department');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->date('dob')->nullable()->after('mother_name');
            $table->string('contact_number')->nullable()->after('dob');
            $table->string('guardian_number')->nullable()->after('contact_number');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('guardian_number');

            // Address Details
            $table->text('address')->nullable()->after('gender');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode')->nullable()->after('state');

            // Previous Employment
            $table->string('last_company_name')->nullable()->after('pincode');
            $table->decimal('last_salary_in_hand', 10, 2)->nullable()->after('last_company_name');
            $table->decimal('last_salary_ctc', 10, 2)->nullable()->after('last_salary_in_hand');
            $table->string('uan_number')->nullable()->after('last_salary_ctc');

            // Bank Details
            $table->string('bank_name')->nullable()->after('uan_number');
            $table->string('ifsc_code')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('ifsc_code');

            // Selfie
            $table->string('selfie')->nullable()->after('bank_account_number');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'mother_name',
                'dob',
                'contact_number',
                'guardian_number',
                'gender',
                'address',
                'city',
                'state',
                'pincode',
                'last_company_name',
                'last_salary_in_hand',
                'last_salary_ctc',
                'uan_number',
                'bank_name',
                'ifsc_code',
                'bank_account_number',
                'selfie'
            ]);
        });
    }
};