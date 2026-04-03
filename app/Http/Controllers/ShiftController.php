<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $shifts = Shift::latest()->paginate(10);

        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        return view('shifts.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'break_start_time' => ['nullable', 'date_format:H:i'],
            'break_end_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'grace_minutes' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validationError = $this->validateShiftTimes($validated);

        if ($validationError) {
            return back()->withErrors($validationError)->withInput();
        }

        $isOvernight = $this->isOvernightShift(
            $validated['start_time'],
            $validated['end_time']
        );

        Shift::create([
            'name' => $validated['name'],
            'start_time' => $validated['start_time'],
            'break_start_time' => $validated['break_start_time'] ?? null,
            'break_end_time' => $validated['break_end_time'] ?? null,
            'end_time' => $validated['end_time'],
            'is_overnight' => $isOvernight,
            'grace_minutes' => $validated['grace_minutes'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    public function edit(Shift $shift)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'break_start_time' => ['nullable', 'date_format:H:i'],
            'break_end_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'grace_minutes' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validationError = $this->validateShiftTimes($validated);

        if ($validationError) {
            return back()->withErrors($validationError)->withInput();
        }

        $isOvernight = $this->isOvernightShift(
            $validated['start_time'],
            $validated['end_time']
        );

        $shift->update([
            'name' => $validated['name'],
            'start_time' => $validated['start_time'],
            'break_start_time' => $validated['break_start_time'] ?? null,
            'break_end_time' => $validated['break_end_time'] ?? null,
            'end_time' => $validated['end_time'],
            'is_overnight' => $isOvernight,
            'grace_minutes' => $validated['grace_minutes'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('shifts.index')
            ->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        if ($shift->employees()->count() > 0) {
            return back()->with('error', 'This shift is assigned to employees and cannot be deleted.');
        }

        $shift->delete();

        return redirect()
            ->route('shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }

    private function validateShiftTimes(array $validated): ?array
    {
        $startTime = $validated['start_time'];
        $endTime = $validated['end_time'];
        $breakStart = $validated['break_start_time'] ?? null;
        $breakEnd = $validated['break_end_time'] ?? null;

        if ($startTime === $endTime) {
            return [
                'end_time' => 'Shift start time and end time cannot be the same.',
            ];
        }

        if (($breakStart && !$breakEnd) || (!$breakStart && $breakEnd)) {
            return [
                'break_end_time' => 'Both break start time and break end time are required together.',
            ];
        }

        if ($breakStart && $breakEnd) {
            if ($breakStart === $breakEnd) {
                return [
                    'break_end_time' => 'Break start time and break end time cannot be the same.',
                ];
            }

            $shiftStart = Carbon::createFromFormat('H:i', $startTime);
            $shiftEnd = Carbon::createFromFormat('H:i', $endTime);

            if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
                $shiftEnd->addDay();
            }

            $breakStartAt = Carbon::createFromFormat('H:i', $breakStart);
            $breakEndAt = Carbon::createFromFormat('H:i', $breakEnd);

            // Overnight shift case: after-midnight break
            if ($shiftEnd->isNextDay()) {
                if ($breakStartAt->lessThan($shiftStart)) {
                    $breakStartAt->addDay();
                }

                if ($breakEndAt->lessThanOrEqualTo($shiftStart) || $breakEndAt->lessThanOrEqualTo($breakStartAt)) {
                    $breakEndAt->addDay();
                }
            } else {
                if ($breakEndAt->lessThanOrEqualTo($breakStartAt)) {
                    return [
                        'break_end_time' => 'For day shift, break end time must be greater than break start time.',
                    ];
                }
            }

            if ($breakStartAt->lessThan($shiftStart) || $breakEndAt->greaterThan($shiftEnd)) {
                return [
                    'break_end_time' => 'Break time must be within shift timing.',
                ];
            }
        }

        return null;
    }

    private function isOvernightShift(string $startTime, string $endTime): bool
    {
        return Carbon::createFromFormat('H:i', $endTime)
            ->lessThanOrEqualTo(Carbon::createFromFormat('H:i', $startTime));
    }
}