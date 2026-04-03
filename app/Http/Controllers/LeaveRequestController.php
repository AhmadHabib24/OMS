<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\LeaveBalanceService;
use App\Services\AppNotificationService;

class LeaveRequestController extends Controller
{
    protected LeaveBalanceService $leaveBalanceService;
    protected AppNotificationService $appNotificationService;

    public function __construct(
        LeaveBalanceService $leaveBalanceService,
        AppNotificationService $appNotificationService
    ) {
        $this->leaveBalanceService = $leaveBalanceService;
        $this->appNotificationService = $appNotificationService;
    }
    public function myLeaves(Request $request)
    {
        abort_unless(auth()->user()->can('apply leave'), 403);
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not linked.');
        }

        $query = $employee->leaveRequests()->with(['leaveType', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->latest()->paginate(10)->withQueryString();

        return view('leave-requests.my-leaves', compact('leaveRequests'));
    }

    public function create()
    {
            abort_unless(auth()->user()->can('apply leave'), 403);
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not linked.');
        }

        $leaveTypes = LeaveType::where('is_active', true)
            ->orderBy('name')
            ->get();

        $balances = $this->leaveBalanceService->getYearlyBalance($employee);

        return view('leave-requests.create', compact('leaveTypes', 'balances'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('apply leave'), 403);
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not linked.');
        }

        if ($employee->status !== 'active') {
            return back()->with('error', 'Only active employees can apply for leave.');
        }

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:2000',
        ]);

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $overlappingLeave = LeaveRequest::where('employee_id', $employee->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate->toDateString(), $endDate->toDateString()])
                    ->orWhereBetween('end_date', [$startDate->toDateString(), $endDate->toDateString()])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate->toDateString())
                            ->where('end_date', '>=', $endDate->toDateString());
                    });
            })
            ->exists();

        if ($overlappingLeave) {
            return back()
                ->withInput()
                ->withErrors(['start_date' => 'You already have a pending or approved leave in this date range.']);
        }

        $balance = $this->leaveBalanceService->getTypeBalance($employee, (int) $request->leave_type_id);

        if ($balance['allowed_days'] > 0 && $totalDays > $balance['remaining_days']) {
            return back()
                ->withInput()
                ->withErrors([
                    'leave_type_id' => "Insufficient leave balance. Remaining {$balance['name']}: {$balance['remaining_days']} day(s)."
                ]);
        }

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_days' => $totalDays,
            'status' => 'pending',
            'reason' => $request->reason,
        ]);

        $leaveRequest->load('leaveType');

        $this->appNotificationService->notifyAdmins(
            'leave_submitted',
            'New Leave Request Submitted',
            "{$employee->full_name} submitted a {$leaveRequest->leaveType->name} request from {$leaveRequest->start_date->format('Y-m-d')} to {$leaveRequest->end_date->format('Y-m-d')}.",
            route('leave-requests.index'),
            [
                'leave_request_id' => $leaveRequest->id,
                'employee_id' => $employee->id,
            ]
        );

        return redirect()
            ->route('leave-requests.my')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function cancel(LeaveRequest $leaveRequest)
    {
        abort_unless(auth()->user()->can('cancel own leave'), 403);
        $employee = auth()->user()->employee;

        if (!$employee || $leaveRequest->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Only pending leave requests can be cancelled.');
        }

        $leaveRequest->update([
            'status' => 'cancelled',
        ]);
        $this->appNotificationService->notifyAdmins(
            'leave_cancelled',
            'Leave Request Cancelled',
            "{$employee->full_name} cancelled a pending leave request from {$leaveRequest->start_date->format('Y-m-d')} to {$leaveRequest->end_date->format('Y-m-d')}.",
            route('leave-requests.index'),
            [
                'leave_request_id' => $leaveRequest->id,
                'employee_id' => $employee->id,
            ]
        );

        return redirect()
            ->route('leave-requests.my')
            ->with('success', 'Leave request cancelled successfully.');
    }

    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('view leave requests'), 403);
        $query = LeaveRequest::with(['employee', 'leaveType', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('start_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('end_date', '<=', $request->to);
        }

        $leaveRequests = $query->latest()->paginate(15)->withQueryString();

        $summaryBase = LeaveRequest::query();

        if ($request->filled('employee_id')) {
            $summaryBase->where('employee_id', $request->employee_id);
        }

        if ($request->filled('leave_type_id')) {
            $summaryBase->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('from')) {
            $summaryBase->whereDate('start_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $summaryBase->whereDate('end_date', '<=', $request->to);
        }

        $summary = [
            'total' => (clone $summaryBase)->count(),
            'pending' => (clone $summaryBase)->where('status', 'pending')->count(),
            'approved' => (clone $summaryBase)->where('status', 'approved')->count(),
            'rejected' => (clone $summaryBase)->where('status', 'rejected')->count(),
            'cancelled' => (clone $summaryBase)->where('status', 'cancelled')->count(),
        ];

        $employees = \App\Models\Employee::where('status', 'active')
            ->orderBy('full_name')
            ->get();

        $leaveTypes = \App\Models\LeaveType::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('leave-requests.index', compact(
            'leaveRequests',
            'summary',
            'employees',
            'leaveTypes'
        ));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        abort_unless(auth()->user()->can('approve leave requests'), 403);
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Only pending leave requests can be approved.');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'admin_remarks' => 'Approved by admin.',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $leaveRequest->load(['employee', 'leaveType']);

        if ($leaveRequest->employee && $leaveRequest->employee->user_id) {
            $this->appNotificationService->create(
                $leaveRequest->employee->user_id,
                'leave_approved',
                'Leave Request Approved',
                "Your {$leaveRequest->leaveType->name} request from {$leaveRequest->start_date->format('Y-m-d')} to {$leaveRequest->end_date->format('Y-m-d')} has been approved.",
                route('leave-requests.my'),
                [
                    'leave_request_id' => $leaveRequest->id,
                ]
            );
        }

        return back()->with('success', 'Leave request approved successfully.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        abort_unless(auth()->user()->can('reject leave requests'), 403);
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Only pending leave requests can be rejected.');
        }

        $request->validate([
            'admin_remarks' => 'required|string|max:2000',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'admin_remarks' => $request->admin_remarks,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $leaveRequest->load(['employee', 'leaveType']);

        if ($leaveRequest->employee && $leaveRequest->employee->user_id) {
            $this->appNotificationService->create(
                $leaveRequest->employee->user_id,
                'leave_rejected',
                'Leave Request Rejected',
                "Your {$leaveRequest->leaveType->name} request from {$leaveRequest->start_date->format('Y-m-d')} to {$leaveRequest->end_date->format('Y-m-d')} was rejected. Remark: {$leaveRequest->admin_remarks}",
                route('leave-requests.my'),
                [
                    'leave_request_id' => $leaveRequest->id,
                ]
            );
        }

        return back()->with('success', 'Leave request rejected successfully.');
    }
    public function balance()
    {
            abort_unless(auth()->user()->can('view own leave balance'), 403);
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not linked.');
        }

        $balances = $this->leaveBalanceService->getYearlyBalance($employee);

        return view('leave-requests.balance', compact('balances'));
    }
}