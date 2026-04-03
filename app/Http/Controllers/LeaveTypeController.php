<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->can('view leave types'), 403);
        $leaveTypes = LeaveType::latest()->paginate(10);

        return view('leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('create leave types'), 403);
        return view('leave-types.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('create leave types'), 403);
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'code' => 'required|string|max:20|unique:leave_types,code',
            'default_days' => 'required|integer|min:0',
            'is_paid' => 'required|boolean',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        LeaveType::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'default_days' => $request->default_days,
            'is_paid' => $request->boolean('is_paid'),
            'is_active' => $request->boolean('is_active'),
            'description' => $request->description,
        ]);

        return redirect()
            ->route('leave-types.index')
            ->with('success', 'Leave type created successfully.');
    }

    public function edit(LeaveType $leaveType)
    {
        abort_unless(auth()->user()->can('edit leave types'), 403);
        return view('leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        abort_unless(auth()->user()->can('edit leave types'), 403);
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'code' => 'required|string|max:20|unique:leave_types,code,' . $leaveType->id,
            'default_days' => 'required|integer|min:0',
            'is_paid' => 'required|boolean',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $leaveType->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'default_days' => $request->default_days,
            'is_paid' => $request->boolean('is_paid'),
            'is_active' => $request->boolean('is_active'),
            'description' => $request->description,
        ]);

        return redirect()
            ->route('leave-types.index')
            ->with('success', 'Leave type updated successfully.');
    }

    public function destroy(LeaveType $leaveType)
    {
        abort_unless(auth()->user()->can('delete leave types'), 403);
        if ($leaveType->leaveRequests()->exists()) {
            return back()->with('error', 'This leave type is already used in leave requests and cannot be deleted.');
        }

        $leaveType->delete();

        return redirect()
            ->route('leave-types.index')
            ->with('success', 'Leave type deleted successfully.');
    }
}