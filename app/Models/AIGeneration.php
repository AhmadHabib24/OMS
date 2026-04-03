<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AIGeneration extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'model',
        'user_id',
        'lead_id',
        'email_campaign_id',
        'input_payload',
        'output_payload',
        'prompt_tokens',
        'response_tokens',
        'total_tokens',
        'status',
        'error_message',
    ];
    protected $table = 'ai_generations';

    protected $casts = [
        'input_payload' => 'array',
        'output_payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function campaign()
    {
        return $this->belongsTo(EmailCampaign::class, 'email_campaign_id');
    }
}