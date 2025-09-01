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
       if (!Schema::hasTable('attendance')) {
            Schema::create('attendance', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id')->nullable(); // keep nullable for users
                $table->unsignedBigInteger('user_id')->nullable();     // new column for users
                $table->date('date');
                $table->time('check_in')->nullable();
                $table->time('check_out')->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade'); 
                $table->foreign('user_id')->references('id')->on('userasll')->onDelete('cascade');
            });
        }
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
