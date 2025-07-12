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
    Schema::create('employee', function (Blueprint $table) {
        $table->id();
        $table->string('first_name', 100);
        $table->string('last_name', 100);
        $table->string('email', 150)->unique();
        $table->string('phone', 20)->unique();
        $table->text('address');
        $table->date('date_of_birth');
        $table->enum('gender', ['Male', 'Female', 'Other']);
        $table->unsignedBigInteger('department_id');
        $table->unsignedBigInteger('designation_id');
        $table->date('hire_date');
        $table->decimal('salary', 10, 2);
        $table->string('profile_picture', 255)->nullable();
        $table->enum('status', ['Active', 'Inactive'])->default('Active');
        $table->timestamps();

        // Foreign keys
        $table->foreign('department_id')->references('id')->on('department')->onDelete('cascade');
        $table->foreign('designation_id')->references('id')->on('designation')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
