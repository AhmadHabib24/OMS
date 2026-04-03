<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficeSetting;

class OfficeSettingSeeder extends Seeder
{
    public function run(): void
    {
        if (!OfficeSetting::exists()) {
            OfficeSetting::create([
                'office_name' => 'Main Office Lahore',
                'office_latitude' => 31.5203700,
                'office_longitude' => 74.3587490,
                'allowed_radius_meters' => 150,
                'office_start_time' => '09:00:00',
                'late_after' => '09:15:00',
                'selfie_required' => true,
                'location_required' => true,
                'device_tracking_enabled' => true,
            ]);
        }
    }
}