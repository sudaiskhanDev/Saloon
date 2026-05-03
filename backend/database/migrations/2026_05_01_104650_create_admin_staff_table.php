<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_staffs', function (Blueprint $table) {
            $table->id('admin_staff_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'staff']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_staffs');
    }
};