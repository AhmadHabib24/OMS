<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeadReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('view lead reports'), 403);
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfMonth();

        $query = Lead::with('assignedEmployee')
            ->whereBetween('created_at', [$from, $to]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->boolean('follow_up_due_only')) {
            $query->whereDate('next_follow_up_date', '<=', today())
                ->whereNotIn('status', ['won', 'lost']);
        }

        if ($request->filled('archived')) {
            if ($request->archived === 'yes') {
                $query->where('is_archived', true);
            } elseif ($request->archived === 'no') {
                $query->where('is_archived', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('lead_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $records = $query->latest()->paginate(15)->withQueryString();

        $summary = [
            'total' => (clone $query)->count(),
            'new' => (clone $query)->where('status', 'new')->count(),
            'contacted' => (clone $query)->where('status', 'contacted')->count(),
            'qualified' => (clone $query)->where('status', 'qualified')->count(),
            'proposal_sent' => (clone $query)->where('status', 'proposal_sent')->count(),
            'won' => (clone $query)->where('status', 'won')->count(),
            'lost' => (clone $query)->where('status', 'lost')->count(),
            'high_priority' => (clone $query)->where('priority', 'high')->count(),
            'follow_up_due' => (clone $query)
                ->whereDate('next_follow_up_date', '<=', today())
                ->whereNotIn('status', ['won', 'lost'])
                ->count(),
            'archived' => (clone $query)->where('is_archived', true)->count(),
        ];

        $employees = Employee::where('status', 'active')
            ->orderBy('full_name')
            ->get();

        $sources = Lead::whereNotNull('source')
            ->where('source', '!=', '')
            ->distinct()
            ->orderBy('source')
            ->pluck('source');

        return view('reports.leads', compact(
            'records',
            'summary',
            'employees',
            'sources',
            'from',
            'to'
        ));
    }
}