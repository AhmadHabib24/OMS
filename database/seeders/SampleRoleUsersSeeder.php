<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleRoleUsersSeeder extends Seeder
{
    public function run(): void
    {
        // HR User
        $hrUser = User::updateOrCreate(
            ['email' => 'hr@example.com'],
            [
                'name' => 'HR User',
                'password' => Hash::make('password'),
            ]
        );
        $hrUser->syncRoles(['hr']);

        // Sales User
        $salesUser = User::updateOrCreate(
            ['email' => 'sales@example.com'],
            [
                'name' => 'Sales User',
                'password' => Hash::make('password'),
            ]
        );
        $salesUser->syncRoles(['sales']);

        // Manager User
        $managerUser = User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
            ]
        );
        $managerUser->syncRoles(['manager']);

        // Optional employee records for role users
        $this->createEmployeeIfMissing($hrUser, 'EMP-HR-001', 'Human Resources', 'HR Executive');
        $this->createEmployeeIfMissing($salesUser, 'EMP-SALES-001', 'Sales', 'Sales Executive');
        $this->createEmployeeIfMissing($managerUser, 'EMP-MGR-001', 'Management', 'Team Manager');
    }

    protected function createEmployeeIfMissing(User $user, string $employeeCode, string $department, string $designation): void
    {
        Employee::updateOrCreate(
            ['user_id' => $user->id],
            [
                'employee_code' => $employeeCode,
                'full_name' => $user->name,
                'email' => $user->email,
                'phone' => null,
                'department' => $department,
                'designation' => $designation,
                'joining_date' => now()->toDateString(),
                'status' => 'active',
            ]
        );
    }
}