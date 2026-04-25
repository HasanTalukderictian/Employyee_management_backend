<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        // Users টেবিলের সাথে employee_id এর রিলেশন
        $table->foreignId('employee_id')->constrained('employee')->onDelete('cascade');
        $table->date('attendance_date');
        $table->time('check_in')->nullable();
        $table->time('check_out')->nullable();
        $table->string('status')->default('Present'); // Present, Late, Absent
        $table->string('location_coords')->nullable(); // সিকিউরিটির জন্য লোকেশন রাখলে ভালো
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
