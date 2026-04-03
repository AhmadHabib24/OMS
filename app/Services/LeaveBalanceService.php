<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\LeaveRequest;

class LeaveBalanceService
{
    public function getYearlyBalance(Employee $employee, ?int $year = null): array
    {
        $year = $year ?: now()->year;

        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

        $balances = [];

        foreach ($leaveTypes as $type) {
            $usedDays = LeaveRequest::where('employee_id', $employee->id)
                ->where('leave_type_id', $type->id)
                ->where('status', 'approved')
                ->whereYear('start_date', $year)
                ->sum('total_days');

            $allowedDays = (int) $type->default_days;
            $remainingDays = max(0, $allowedDays - $usedDays);

            $balances[] = [
                'leave_type_id' => $type->id,
                'name' => $type->name,
                'code' => $type->code,
                'is_paid' => $type->is_paid,
                'allowed_days' => $allowedDays,
                'used_days' => (int) $usedDays,
                'remaining_days' => (int) $remainingDays,
            ];
        }

        return $balances;
    }

    public function getTypeBalance(Employee $employee, int $leaveTypeId, ?int $year = null): array
    {
        $year = $year ?: now()->year;

        $type = LeaveType::findOrFail($leaveTypeId);

        $usedDays = LeaveRequest::where('employee_id', $employee->id)
            ->where('leave_type_id', $leaveTypeId)
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->sum('total_days');

        $allowedDays = (int) $type->default_days;
        $remainingDays = max(0, $allowedDays - $usedDays);

        return [
            'leave_type_id' => $type->id,
            'name' => $type->name,
            'code' => $type->code,
            'is_paid' => $type->is_paid,
            'allowed_days' => $allowedDays,
            'used_days' => (int) $usedDays,
            'remaining_days' => (int) $remainingDays,
        ];
    }
}