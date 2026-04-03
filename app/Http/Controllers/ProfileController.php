<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OfficeSetting;
use App\Services\AttendancePrivacyService;
use App\Services\AppNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected AttendancePrivacyService $privacyService,
        protected AppNotificationService $appNotificationService
    ) {
    }

    public function employeeProfile()
    {
        abort_unless(auth()->user()->hasRole('employee'), 403);

        $user = auth()->user();
        $employee = $user->employee;

        return view('profile.employee', compact('employee'));
    }

    public function myAttendance()
    {
        abort_unless(auth()->user()->hasRole('employee'), 403);
        abort_unless(feature_enabled('attendance_module_enabled'), 403);

        $user = auth()->user();
        $employee = $user->employee;
        $attendances = $employee
            ? $employee->attendances()->with('shift')->latest()->paginate(10)
            : null;

        $attendances = $employee
            ? $employee->attendances()->latest()->paginate(10)
            : null;

        return view('profile.my-attendance', compact('employee', 'attendances'));
    }

    public function checkInForm()
    {
        abort_unless(auth()->user()->can('mark self attendance'), 403);
        abort_unless(feature_enabled('attendance_module_enabled'), 403);

        $user = auth()->user();
        $employee = $user->employee;
        $employee = $user->employee?->load('shift');

        if (!$employee) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Your employee profile is not linked yet.');
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        $officeSetting = OfficeSetting::first();

        return view('profile.check-in', compact('employee', 'todayAttendance', 'officeSetting'));
    }

    public function storeCheckIn(Request $request)
    {
        abort_unless(auth()->user()->can('mark self attendance'), 403);
        abort_unless(feature_enabled('attendance_module_enabled'), 403);

        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Your employee profile is not linked yet.');
        }

        if ($employee->status !== 'active') {
            return back()->with('error', 'Only active employees can mark attendance.');
        }

        if (!$employee->shift) {
            return back()->with('error', 'No shift assigned to your profile. Please contact admin.');
        }

        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $alreadyMarked = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->exists();

        if ($alreadyMarked) {
            return back()->with('error', 'Attendance already marked for today.');
        }

        $officeSetting = OfficeSetting::first();
        $shift = $employee->shift;
        $checkIn = now();

        $distance = null;
        $outsideOffice = false;
        $isSuspicious = false;
        $reasons = [];

        if ($officeSetting && $request->filled('latitude') && $request->filled('longitude')) {
            $distance = $this->privacyService->calculateDistanceInMeters(
                $request->latitude,
                $request->longitude,
                $officeSetting->office_latitude,
                $officeSetting->office_longitude
            );

            $outsideOffice = $this->privacyService->isOutsideAllowedRadius(
                $distance,
                $officeSetting->allowed_radius ?? 0
            );
        }

        if ($outsideOffice && !$request->hasFile('photo')) {
            return back()->withErrors([
                'photo' => 'Selfie is required when marking attendance outside office location.',
            ]);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance_photos', 'public');
        }

        $shiftStart = Carbon::parse(today()->format('Y-m-d') . ' ' . $shift->start_time);
        if ($shift->is_overnight && $checkIn->lt($shiftStart)) {
            $shiftStart->subDay();
        }

        $lateThreshold = $shiftStart->copy()->addMinutes($shift->grace_minutes ?? 0);

        $status = 'present';
        $lateMinutes = 0;

        if ($checkIn->gt($lateThreshold)) {
            $status = 'late';
            $lateMinutes = $checkIn->diffInMinutes($shiftStart);
        }

        $agent = new Agent();

        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'attendance_date' => today(),
            'check_in' => $checkIn->format('H:i:s'),
            'check_out' => null,
            'status' => $status,
            'late_minutes' => $lateMinutes,
            'overtime_minutes' => 0,
            'ip_address' => $request->ip(),
            'device_name' => $agent->device(),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'user_agent' => $request->userAgent(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'distance_from_office' => $distance,
            'photo_path' => $photoPath,
            'privacy_note' => $outsideOffice
                ? 'Checked in outside office radius; selfie required.'
                : 'Checked in within office radius; selfie not required.',
            'is_suspicious' => $isSuspicious,
            'suspicious_reason' => !empty($reasons) ? implode(', ', $reasons) : null,
        ]);

        if ($attendance->is_suspicious) {
            $this->appNotificationService->notifyAdmins(
                'suspicious_attendance',
                'Suspicious Attendance Detected',
                "{$employee->full_name} marked suspicious attendance on {$attendance->attendance_date->format('Y-m-d')}. Reason: {$attendance->suspicious_reason}",
                route('attendances.index'),
                [
                    'attendance_id' => $attendance->id,
                    'employee_id' => $employee->id,
                ]
            );
        }

        return redirect()
            ->route('profile.attendance')
            ->with('success', 'Your check-in has been marked successfully.');
    }

    public function checkOutForm()
    {
        abort_unless(auth()->user()->can('mark self checkout'), 403);
        abort_unless(feature_enabled('attendance_module_enabled'), 403);


        $user = auth()->user();
        $employee = $user->employee;
        $employee = $user->employee?->load('shift');

        if (!$employee) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Your employee profile is not linked yet.');
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (!$todayAttendance) {
            return redirect()
                ->route('profile.checkin.form')
                ->with('error', 'Please mark check-in first.');
        }

        return view('profile.check-out', compact('employee', 'todayAttendance'));
    }

    public function storeCheckOut(Request $request)
    {
        abort_unless(auth()->user()->can('mark self checkout'), 403);
        abort_unless(feature_enabled('attendance_module_enabled'), 403);

        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Your employee profile is not linked yet.');
        }

        if (!$employee->shift) {
            return back()->with('error', 'No shift assigned to your profile. Please contact admin.');
        }

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (!$attendance) {
            return redirect()
                ->route('profile.checkin.form')
                ->with('error', 'Please mark check-in first.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Check-out already submitted for today.');
        }

        $shift = $employee->shift;
        $checkOut = now();

        $checkInDateTime = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $attendance->check_in);
        $checkOutDateTime = $checkOut->copy();

        if ($checkOutDateTime->lessThanOrEqualTo($checkInDateTime)) {
            $checkOutDateTime->addDay();
        }

        $shiftEnd = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $shift->end_time);
        if ($shift->is_overnight) {
            $shiftEnd->addDay();
        }

        $overtimeMinutes = 0;
        if ($checkOutDateTime->gt($shiftEnd)) {
            $overtimeMinutes = $checkOutDateTime->diffInMinutes($shiftEnd);
        }

        $breakMinutes = 0;

        if ($shift->break_start_time && $shift->break_end_time) {
            $breakStart = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $shift->break_start_time);
            $breakEnd = Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $shift->break_end_time);

            if ($shift->is_overnight) {
                if ($breakStart->lessThan($checkInDateTime)) {
                    $breakStart->addDay();
                }

                if ($breakEnd->lessThanOrEqualTo($breakStart)) {
                    $breakEnd->addDay();
                }
            }

            $overlapStart = $checkInDateTime->copy()->max($breakStart);
            $overlapEnd = $checkOutDateTime->copy()->min($breakEnd);

            if ($overlapEnd->gt($overlapStart)) {
                $breakMinutes = $overlapEnd->diffInMinutes($overlapStart);
            }
        }

        $totalWorkedSpan = $checkOutDateTime->diffInMinutes($checkInDateTime);
        $workedMinutes = max(0, $totalWorkedSpan - $breakMinutes);

        $attendance->update([
            'check_out' => $checkOut->format('H:i:s'),
            'overtime_minutes' => $overtimeMinutes,
            'break_minutes' => $breakMinutes,
            'worked_minutes' => $workedMinutes,
            'privacy_note' => trim(($attendance->privacy_note ?? '') . ' Check-out submitted by employee.'),
        ]);

        return redirect()
            ->route('profile.attendance')
            ->with('success', 'Your check-out has been submitted successfully.');
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}