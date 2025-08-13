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
        Schema::create('employee_leave', function (Blueprint $table) {
           $table->unsignedBigInteger('employee_id');
            $table->integer('total_leave')->default(0);
            $table->integer('taken_leave')->default(0);
            $table->integer('remaining_leave')->default(0);
            $table->enum('leave_type', ['Paid', 'Unpaid'])->default('Paid'); // Only Paid or Unpaid
            $table->timestamps();

            // Foreign key
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leaves');
    }
};
