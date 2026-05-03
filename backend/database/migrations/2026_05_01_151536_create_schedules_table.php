<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id');

            $table->unsignedBigInteger('admin_staff_id');

            $table->date('work_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->timestamps();

            // Foreign key
            $table->foreign('admin_staff_id')
                  ->references('admin_staff_id')
                  ->on('admin_staffs')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};