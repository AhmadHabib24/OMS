<?php

namespace App\Http\Controllers;

use App\Mail\CampaignMail;
use App\Models\EmailCampaign;
use App\Models\EmailLog;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Services\AppNotificationService;

class EmailCampaignController extends Controller
{
    protected AppNotificationService $appNotificationService;

    public function __construct(AppNotificationService $appNotificationService)
    {
        $this->appNotificationService = $appNotificationService;
    }

    public function index()
    {
        abort_unless(auth()->user()->can('view email campaigns'), 403);
        $campaigns = EmailCampaign::with('creator')->latest()->paginate(10);

        return view('email-campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('create email campaigns'), 403);
         
        $leads = Lead::whereNotNull('email')
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();

        return view('email-campaigns.create', compact('leads'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('create email campaigns'), 403);
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'lead_ids' => 'required|array|min:1',
            'lead_ids.*' => 'exists:leads,id',
        ]);

        $campaign = EmailCampaign::create([
            'campaign_code' => 'CMP-' . strtoupper(Str::random(6)),
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => 'draft',
            'total_recipients' => count($request->lead_ids),
            'sent_count' => 0,
            'failed_count' => 0,
            'created_by' => auth()->id(),
        ]);

        $leads = Lead::whereIn('id', $request->lead_ids)->get();

        foreach ($leads as $lead) {
            EmailLog::create([
                'email_campaign_id' => $campaign->id,
                'lead_id' => $lead->id,
                'recipient_email' => $lead->email,
                'recipient_name' => $lead->name,
                'status' => 'pending',
            ]);
        }

        return redirect()
            ->route('email-campaigns.show', $campaign)
            ->with('success', 'Email campaign created successfully.');
    }

    public function show(EmailCampaign $email_campaign)
    {
            abort_unless(auth()->user()->can('view email campaigns'), 403);
        $email_campaign->load(['creator', 'logs.lead']);

        return view('email-campaigns.show', [
            'campaign' => $email_campaign
        ]);
    }

    public function edit(EmailCampaign $email_campaign)
    {
            abort_unless(auth()->user()->can('edit email campaigns'), 403);
        if ($email_campaign->status !== 'draft') {
            return redirect()
                ->route('email-campaigns.index')
                ->with('error', 'Only draft campaigns can be edited.');
        }

        return view('email-campaigns.edit', [
            'campaign' => $email_campaign
        ]);
    }

    public function update(Request $request, EmailCampaign $email_campaign)
    {
            abort_unless(auth()->user()->can('edit email campaigns'), 403);
        if ($email_campaign->status !== 'draft') {
            return redirect()
                ->route('email-campaigns.index')
                ->with('error', 'Only draft campaigns can be updated.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $email_campaign->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return redirect()
            ->route('email-campaigns.show', $email_campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(EmailCampaign $email_campaign)
    {
        abort_unless(auth()->user()->can('delete email campaigns'), 403);
        if ($email_campaign->status === 'sending') {
            return back()->with('error', 'Sending campaign cannot be deleted.');
        }

        $email_campaign->delete();

        return redirect()
            ->route('email-campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    public function send(EmailCampaign $email_campaign)
    {
        abort_unless(auth()->user()->can('send email campaigns'), 403);
        if ($email_campaign->logs()->count() === 0) {
            return back()->with('error', 'No recipients found for this campaign.');
        }

        $email_campaign->update([
            'status' => 'sending',
            'sent_count' => 0,
            'failed_count' => 0,
        ]);

        $sentCount = 0;
        $failedCount = 0;

        foreach ($email_campaign->logs as $log) {
            try {
                Mail::to($log->recipient_email)->send(
                    new CampaignMail($email_campaign, $log->lead)
                );

                $log->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'error_message' => null,
                ]);

                $sentCount++;
            } catch (\Throwable $e) {
                $log->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                $failedCount++;
            }
        }

        $email_campaign->update([
            'status' => $failedCount > 0 && $sentCount === 0 ? 'failed' : 'sent',
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'sent_at' => now(),
        ]);

        $this->appNotificationService->notifyAdmins(
            'campaign_sent',
            'Email Campaign Processed',
            "Campaign {$email_campaign->name} finished. Sent: {$sentCount}, Failed: {$failedCount}.",
            route('email-campaigns.show', $email_campaign),
            [
                'campaign_id' => $email_campaign->id,
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
            ]
        );

        return redirect()
            ->route('email-campaigns.show', $email_campaign)
            ->with('success', 'Campaign sending process completed.');
    }
}