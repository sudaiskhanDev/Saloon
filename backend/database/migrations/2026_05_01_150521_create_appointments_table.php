<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_staff_id');
            $table->unsignedBigInteger('service_id');

            $table->date('date');
            $table->time('time');

            $table->enum('status', ['booked', 'cancelled', 'completed'])->default('booked');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('admin_staff_id')->references('admin_staff_id')->on('admin_staffs')->onDelete('cascade');
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};