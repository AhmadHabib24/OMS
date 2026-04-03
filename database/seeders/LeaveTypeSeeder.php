<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Casual Leave',
                'code' => 'CL',
                'default_days' => 12,
                'is_paid' => true,
                'is_active' => true,
                'description' => 'Short personal leave for daily needs.',
            ],
            [
                'name' => 'Sick Leave',
                'code' => 'SL',
                'default_days' => 10,
                'is_paid' => true,
                'is_active' => true,
                'description' => 'Leave for illness or medical reasons.',
            ],
            [
                'name' => 'Annual Leave',
                'code' => 'AL',
                'default_days' => 18,
                'is_paid' => true,
                'is_active' => true,
                'description' => 'Yearly planned leave.',
            ],
            [
                'name' => 'Unpaid Leave',
                'code' => 'UL',
                'default_days' => 0,
                'is_paid' => false,
                'is_active' => true,
                'description' => 'Leave without salary deduction protection.',
            ],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}