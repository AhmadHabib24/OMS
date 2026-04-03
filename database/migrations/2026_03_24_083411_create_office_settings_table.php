<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_settings', function (Blueprint $table) {
            $table->id();
            $table->string('office_name')->default('Main Office');
            $table->decimal('office_latitude', 10, 7)->nullable();
            $table->decimal('office_longitude', 10, 7)->nullable();
            $table->integer('allowed_radius_meters')->default(150);
            $table->time('office_start_time')->default('09:00:00');
            $table->time('late_after')->default('09:15:00');
            $table->boolean('selfie_required')->default(true);
            $table->boolean('location_required')->default(true);
            $table->boolean('device_tracking_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_settings');
    }
};