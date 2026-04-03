<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('view employees'), 403);
        $query = Employee::query()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $employees = $query->latest()->paginate(10)->withQueryString();
        $departments = Employee::whereNotNull('department')->distinct()->pluck('department');

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        // abort_unless(auth()->user()->can('create employees'), 403);
        return view('employees.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('create employees'), 403);
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('employee');

        Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'EMP-' . strtoupper(Str::random(6)),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'designation' => $request->designation,
            'joining_date' => $request->joining_date,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee and login account created successfully.');
    }

    public function show(Employee $employee)
    {
        abort_unless(auth()->user()->can('view employees'), 403);
        $employee->load('user', 'attendances');

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        abort_unless(auth()->user()->can('edit employees'), 403);

        $employee->load('user');

        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        abort_unless(auth()->user()->can('edit employees'), 403);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id . '|unique:users,email,' . $employee->user_id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($employee->user) {
            $employee->user->update([
                'name' => $request->full_name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $employee->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        }

        $employee->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'designation' => $request->designation,
            'joining_date' => $request->joining_date,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        abort_unless(auth()->user()->can('delete employees'), 403);
        $user = $employee->user;

        $employee->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}