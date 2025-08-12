<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('userall', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->string('password');
            $table->timestamps();

            // Foreign key constraint assuming 'employee' table exists
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('userall');
    }
};
