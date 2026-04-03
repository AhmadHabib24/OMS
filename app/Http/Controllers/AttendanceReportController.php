<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
public function index(Request $request)
{
    abort_unless(auth()->user()->can('view attendance reports'), 403);
    abort_unless(feature_enabled('attendance_module_enabled'), 403);

    $query = \App\Models\Attendance::query()->with(['employee', 'shift']);

    if ($request->filled('date_from')) {
        $query->whereDate('attendance_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('attendance_date', '<=', $request->date_to);
    }

    if ($request->filled('shift_id')) {
        $query->where('shift_id', $request->shift_id);
    }

    $attendances = $query->latest('attendance_date')->paginate(20)->withQueryString();

    $summaryQuery = clone $query;

    $summary = [
        'total_records' => $summaryQuery->count(),
        'present_count' => (clone $query)->where('status', 'present')->count(),
        'late_count' => (clone $query)->where('status', 'late')->count(),
        'total_late_minutes' => (clone $query)->sum('late_minutes'),
        'total_overtime_minutes' => (clone $query)->sum('overtime_minutes'),
        'total_break_minutes' => (clone $query)->sum('break_minutes'),
        'total_worked_minutes' => (clone $query)->sum('worked_minutes'),
    ];

    $shifts = \App\Models\Shift::where('is_active', true)->orderBy('name')->get();

    return view('reports.attendance', compact('attendances', 'summary', 'shifts'));
}
}