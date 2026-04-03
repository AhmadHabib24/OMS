<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('device_name')->nullable()->after('ip_address');
            $table->string('browser')->nullable()->after('device_name');
            $table->string('platform')->nullable()->after('browser');
            $table->string('user_agent')->nullable()->after('platform');
            $table->decimal('distance_from_office', 10, 2)->nullable()->after('longitude');
            $table->boolean('is_suspicious')->default(false)->after('privacy_note');
            $table->string('suspicious_reason')->nullable()->after('is_suspicious');
            $table->text('admin_review_note')->nullable()->after('suspicious_reason');
            $table->timestamp('reviewed_at')->nullable()->after('admin_review_note');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'device_name',
                'browser',
                'platform',
                'user_agent',
                'distance_from_office',
                'is_suspicious',
                'suspicious_reason',
                'admin_review_note',
                'reviewed_at',
            ]);
        });
    }
};