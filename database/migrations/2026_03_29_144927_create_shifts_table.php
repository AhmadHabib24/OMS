<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Morning Shift
            $table->time('start_time'); // e.g. 09:00
            $table->time('break_start_time')->nullable(); // e.g. 13:00
            $table->time('break_end_time')->nullable();   // e.g. 14:00
            $table->time('end_time');   // e.g. 18:00
            $table->unsignedInteger('grace_minutes')->default(0); // e.g. 15
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};