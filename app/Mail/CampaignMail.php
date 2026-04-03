<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public EmailCampaign $campaign;
    public ?Lead $lead;

    public function __construct(EmailCampaign $campaign, ?Lead $lead = null)
    {
        $this->campaign = $campaign;
        $this->lead = $lead;
    }

    public function build()
    {
        return $this->subject($this->campaign->subject)
            ->view('emails.campaign');
    }
}