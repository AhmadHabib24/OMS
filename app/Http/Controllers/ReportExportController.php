<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmailCampaign;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    public function attendance(Request $request): StreamedResponse
    {
        abort_unless(auth()->user()->can('view attendance reports'), 403);
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfMonth();

        $query = Attendance::with('employee')
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()]);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('suspicious_only')) {
            $query->where('is_suspicious', true);
        }

        if ($request->boolean('late_only')) {
            $query->where('status', 'late');
        }

        $filename = 'attendance-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date',
                'Employee Code',
                'Employee Name',
                'Email',
                'Department',
                'Designation',
                'Check In',
                'Check Out',
                'Status',
                'IP Address',
                'Distance From Office',
                'Suspicious',
                'Suspicious Reason',
            ]);

            $query->latest('attendance_date')->chunk(500, function ($records) use ($handle) {
                foreach ($records as $record) {
                    fputcsv($handle, [
                        optional($record->attendance_date)->format('Y-m-d'),
                        $record->employee->employee_code ?? '',
                        $record->employee->full_name ?? '',
                        $record->employee->email ?? '',
                        $record->employee->department ?? '',
                        $record->employee->designation ?? '',
                        $record->check_in ?? '',
                        $record->check_out ?? '',
                        $record->status,
                        $record->ip_address ?? '',
                        $record->distance_from_office ?? '',
                        $record->is_suspicious ? 'Yes' : 'No',
                        $record->suspicious_reason ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function leads(Request $request): StreamedResponse
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

        $filename = 'lead-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Lead Code',
                'Name',
                'Email',
                'Phone',
                'Company',
                'Source',
                'Subject',
                'Status',
                'Priority',
                'Assigned To',
                'Next Follow Up Date',
                'Last Contacted At',
                'Archived',
                'Created At',
            ]);

            $query->latest()->chunk(500, function ($records) use ($handle) {
                foreach ($records as $record) {
                    fputcsv($handle, [
                        $record->lead_code,
                        $record->name,
                        $record->email ?? '',
                        $record->phone ?? '',
                        $record->company ?? '',
                        $record->source ?? '',
                        $record->subject ?? '',
                        $record->status,
                        $record->priority,
                        $record->assignedEmployee->full_name ?? '',
                        optional($record->next_follow_up_date)->format('Y-m-d'),
                        optional($record->last_contacted_at)->format('Y-m-d H:i:s'),
                        $record->is_archived ? 'Yes' : 'No',
                        optional($record->created_at)->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function campaigns(Request $request): StreamedResponse
    {
        abort_unless(auth()->user()->can('view email campaign reports'), 403);
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfMonth();

        $query = EmailCampaign::with('creator')
            ->whereBetween('created_at', [$from, $to]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('campaign_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $filename = 'campaign-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Campaign Code',
                'Campaign Name',
                'Subject',
                'Status',
                'Total Recipients',
                'Sent Count',
                'Failed Count',
                'Created By',
                'Sent At',
                'Created At',
            ]);

            $query->latest()->chunk(500, function ($records) use ($handle) {
                foreach ($records as $record) {
                    fputcsv($handle, [
                        $record->campaign_code,
                        $record->name,
                        $record->subject,
                        $record->status,
                        $record->total_recipients,
                        $record->sent_count,
                        $record->failed_count,
                        $record->creator->name ?? '',
                        optional($record->sent_at)->format('Y-m-d H:i:s'),
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