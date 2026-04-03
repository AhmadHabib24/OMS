<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('view leads'), 403);
        $query = Lead::with('assignedEmployee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('lead_code', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $leads = $query->latest()->paginate(10)->withQueryString();
        $employees = Employee::where('status', 'active')->orderBy('full_name')->get();

        return view('leads.index', compact('leads', 'employees'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('create leads'), 403);
        $employees = Employee::where('status', 'active')->orderBy('full_name')->get();

        return view('leads.create', compact('employees'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('create leads'), 403);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,won,lost',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:employees,id',
            'next_follow_up_date' => 'nullable|date',
        ]);

        Lead::create([
            'lead_code' => 'LEAD-' . strtoupper(Str::random(6)),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'source' => $request->source,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => $request->status,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'next_follow_up_date' => $request->next_follow_up_date,
            'last_contacted_at' => $request->status !== 'new' ? now() : null,
            'is_archived' => false,
        ]);

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        abort_unless(auth()->user()->can('view leads'), 403);
        $lead->load(['assignedEmployee', 'notes.user']);

        $latestAiInsight = \App\Models\AIGeneration::where('type', 'lead_insight')
            ->where('lead_id', $lead->id)
            ->where('status', 'success')
            ->latest()
            ->first();

        return view('leads.show', compact('lead', 'latestAiInsight'));
    }

    public function edit(Lead $lead)
    {
        abort_unless(auth()->user()->can('edit leads'), 403);
        $employees = Employee::where('status', 'active')->orderBy('full_name')->get();

        return view('leads.edit', compact('lead', 'employees'));
    }

    public function update(Request $request, Lead $lead)
    {
        abort_unless(auth()->user()->can('edit leads'), 403);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,won,lost',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:employees,id',
            'next_follow_up_date' => 'nullable|date',
            'is_archived' => 'nullable|boolean',
        ]);

        $lead->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'source' => $request->source,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => $request->status,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'next_follow_up_date' => $request->next_follow_up_date,
            'last_contacted_at' => $request->status !== 'new' ? now() : $lead->last_contacted_at,
            'is_archived' => $request->boolean('is_archived'),
        ]);

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        abort_unless(auth()->user()->can('delete leads'), 403);
        $lead->delete();

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function myLeads(Request $request)
    {
        abort_unless(auth()->user()->can('view own leads'), 403);
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not linked.');
        }

        $query = $employee->assignedLeads()->with('assignedEmployee');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(10)->withQueryString();

        return view('leads.my-leads', compact('leads'));
    }
}