<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('view leave reports'), 403);
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfMonth();

        $query = LeaveRequest::with(['employee', 'leaveType', 'approver'])
            ->whereDate('start_date', '>=', $from->toDateString())
            ->whereDate('end_date', '<=', $to->toDateString());

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->latest()->paginate(15)->withQueryString();

        $summaryBase = LeaveRequest::query()
            ->whereDate('start_date', '>=', $from->toDateString())
            ->whereDate('end_date', '<=', $to->toDateString());

        if ($request->filled('employee_id')) {
            $summaryBase->where('employee_id', $request->employee_id);
        }

        if ($request->filled('leave_type_id')) {
            $summaryBase->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('status')) {
            $summaryBase->where('status', $request->status);
        }

        $summary = [
            'total_requests' => (clone $summaryBase)->count(),
            'pending' => (clone $summaryBase)->where('status', 'pending')->count(),
            'approved' => (clone $summaryBase)->where('status', 'approved')->count(),
            'rejected' => (clone $summaryBase)->where('status', 'rejected')->count(),
            'cancelled' => (clone $summaryBase)->where('status', 'cancelled')->count(),
            'approved_days' => (clone $summaryBase)->where('status', 'approved')->sum('total_days'),
        ];

        $employees = Employee::where('status', 'active')->orderBy('full_name')->get();
        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

        return view('reports.leave-requests', compact(
            'leaveRequests',
            'summary',
            'employees',
            'leaveTypes',
            'from',
            'to'
        ));
    }
    public function export(Request $request)
    {
        abort_unless(auth()->user()->can('view leave reports'), 403);
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfMonth();

        $query = LeaveRequest::with(['employee', 'leaveType', 'approver'])
            ->whereDate('start_date', '>=', $from->toDateString())
            ->whereDate('end_date', '<=', $to->toDateString());

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $filename = 'leave-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Employee Code',
                'Employee Name',
                'Email',
                'Department',
                'Leave Type',
                'Start Date',
                'End Date',
                'Total Days',
                'Status',
                'Reason',
                'Admin Remarks',
                'Approved By',
                'Approved At',
                'Created At',
            ]);

            $query->latest()->chunk(500, function ($records) use ($handle) {
                foreach ($records as $record) {
                    fputcsv($handle, [
                        $record->employee->employee_code ?? '',
                        $record->employee->full_name ?? '',
                        $record->employee->email ?? '',
                        $record->employee->department ?? '',
                        $record->leaveType->name ?? '',
                        optional($record->start_date)->format('Y-m-d'),
                        optional($record->end_date)->format('Y-m-d'),
                        $record->total_days,
                        $record->status,
                        $record->reason ?? '',
                        $record->admin_remarks ?? '',
                        $record->approver->name ?? '',
                        optional($record->approved_at)->format('Y-m-d H:i:s'),
                        optional($record->created_at)->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}