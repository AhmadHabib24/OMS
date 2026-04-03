<?php

namespace App\Http\Controllers;

use App\Models\EmailCampaign;
use App\Models\EmailLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmailCampaignReportController extends Controller
{
    public function index(Request $request)
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

        $records = $query->latest()->paginate(15)->withQueryString();

        $summaryBaseQuery = EmailCampaign::whereBetween('created_at', [$from, $to]);

        if ($request->filled('status')) {
            $summaryBaseQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $summaryBaseQuery->where(function ($q) use ($search) {
                $q->where('campaign_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $summary = [
            'total_campaigns' => (clone $summaryBaseQuery)->count(),
            'draft' => (clone $summaryBaseQuery)->where('status', 'draft')->count(),
            'sending' => (clone $summaryBaseQuery)->where('status', 'sending')->count(),
            'sent' => (clone $summaryBaseQuery)->where('status', 'sent')->count(),
            'failed' => (clone $summaryBaseQuery)->where('status', 'failed')->count(),
            'total_recipients' => (clone $summaryBaseQuery)->sum('total_recipients'),
            'total_sent' => (clone $summaryBaseQuery)->sum('sent_count'),
            'total_failed' => (clone $summaryBaseQuery)->sum('failed_count'),
        ];

        $campaignIds = (clone $summaryBaseQuery)->pluck('id');

        $logSummary = [
            'pending_logs' => EmailLog::whereIn('email_campaign_id', $campaignIds)->where('status', 'pending')->count(),
            'sent_logs' => EmailLog::whereIn('email_campaign_id', $campaignIds)->where('status', 'sent')->count(),
            'failed_logs' => EmailLog::whereIn('email_campaign_id', $campaignIds)->where('status', 'failed')->count(),
        ];

        $recentLogs = EmailLog::with(['campaign', 'lead'])
            ->whereIn('email_campaign_id', $campaignIds)
            ->latest()
            ->take(10)
            ->get();

        return view('reports.email-campaigns', compact(
            'records',
            'summary',
            'logSummary',
            'recentLogs',
            'from',
            'to'
        ));
    }
}