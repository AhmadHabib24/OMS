<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmailCampaign;
use App\Models\Employee;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('view dashboard'), 403);

        if (auth()->user()->hasRole('employee')) {
            return $this->employeeDashboard();
        }

        return $this->adminDashboard($request);
    }

    protected function adminDashboard(Request $request)
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfMonth();

        $employeeStats = [
            'total' => Employee::count(),
            'active' => Employee::where('status', 'active')->count(),
            'inactive' => Employee::where('status', 'inactive')->count(),
            'new_this_month' => Employee::whereBetween('created_at', [$from, $to])->count(),
        ];

        $attendanceStats = [
            'total_records' => Attendance::whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])->count(),
            'present' => Attendance::whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
                ->where('status', 'present')
                ->count(),
            'late' => Attendance::whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
                ->where('status', 'late')
                ->count(),
            'absent' => Attendance::whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
                ->where('status', 'absent')
                ->count(),
            'half_day' => Attendance::whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
                ->where('status', 'half_day')
                ->count(),
            'suspicious' => Attendance::whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
                ->where('is_suspicious', true)
                ->count(),
            'today' => Attendance::whereDate('attendance_date', today())->count(),
            'today_late' => Attendance::whereDate('attendance_date', today())
                ->where('status', 'late')
                ->count(),
        ];

        $leadStats = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'proposal_sent' => Lead::where('status', 'proposal_sent')->count(),
            'won' => Lead::where('status', 'won')->count(),
            'lost' => Lead::where('status', 'lost')->count(),
            'high_priority' => Lead::where('priority', 'high')->count(),
            'follow_up_due' => Lead::whereDate('next_follow_up_date', '<=', today())
                ->whereNotIn('status', ['won', 'lost'])
                ->count(),
            'created_in_range' => Lead::whereBetween('created_at', [$from, $to])->count(),
        ];

        $campaignStats = [
            'total' => EmailCampaign::count(),
            'draft' => EmailCampaign::where('status', 'draft')->count(),
            'sent' => EmailCampaign::where('status', 'sent')->count(),
            'failed' => EmailCampaign::where('status', 'failed')->count(),
            'created_in_range' => EmailCampaign::whereBetween('created_at', [$from, $to])->count(),
            'sent_in_range' => EmailCampaign::whereBetween('sent_at', [$from, $to])->count(),
        ];

        $recentLeads = Lead::latest()->take(5)->get();
        $recentEmployees = Employee::latest()->take(5)->get();
        $recentCampaigns = EmailCampaign::latest()->take(5)->get();
        $recentSuspiciousAttendance = Attendance::with('employee')
            ->where('is_suspicious', true)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'from',
            'to',
            'employeeStats',
            'attendanceStats',
            'leadStats',
            'campaignStats',
            'recentLeads',
            'recentEmployees',
            'recentCampaigns',
            'recentSuspiciousAttendance'
        ));
    }

    protected function employeeDashboard()
    {
        $employee = auth()->user()->employee;

        $todayAttendance = $employee
            ? Attendance::where('employee_id', $employee->id)
                ->whereDate('attendance_date', today())
                ->first()
            : null;

        $monthlyAttendanceCount = $employee
            ? Attendance::where('employee_id', $employee->id)
                ->whereMonth('attendance_date', now()->month)
                ->whereYear('attendance_date', now()->year)
                ->count()
            : 0;

        $monthlyLateCount = $employee
            ? Attendance::where('employee_id', $employee->id)
                ->whereMonth('attendance_date', now()->month)
                ->whereYear('attendance_date', now()->year)
                ->where('status', 'late')
                ->count()
            : 0;

        $myLeadsCount = $employee ? $employee->assignedLeads()->count() : 0;
        $myOpenLeadsCount = $employee
            ? $employee->assignedLeads()->whereNotIn('status', ['won', 'lost'])->count()
            : 0;

        return view('employee-dashboard', compact(
            'employee',
            'todayAttendance',
            'monthlyAttendanceCount',
            'monthlyLateCount',
            'myLeadsCount',
            'myOpenLeadsCount'
        ));
    }
}