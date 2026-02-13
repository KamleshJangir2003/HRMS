<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->enum('category', ['Birthday', 'Office Supplies', 'Travel', 'Food', 'Others']);
            $table->enum('payment_method', ['PhonePe', 'UPI', 'Scanner', 'Others']);
            $table->string('receipt_path')->nullable();
            $table->date('expense_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};