<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\GeminiAIService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function __construct(
        protected GeminiAIService $geminiAIService
    ) {
    }

    public function generateCampaignContent(Request $request)
    {
        abort_unless(auth()->user()->can('generate ai content'), 403);
        $request->validate([
            'goal' => 'required|string|max:500',
            'audience' => 'required|string|max:500',
            'tone' => 'required|string|max:100',
            'offer' => 'required|string|max:1000',
        ]);

        try {
            $result = $this->geminiAIService->generateCampaignContent([
                'goal' => $request->goal,
                'audience' => $request->audience,
                'tone' => $request->tone,
                'offer' => $request->offer,
            ], auth()->id());

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'AI generation failed.',
                'error' => app()->environment('local') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function leadInsights(Lead $lead)
    {
        abort_unless(auth()->user()->can('generate ai content'), 403);
        $cached = \App\Models\AIGeneration::where('type', 'lead_insight')
            ->where('lead_id', $lead->id)
            ->where('status', 'success')
            ->latest()
            ->first();

        if ($cached && $cached->created_at->gt(now()->subHours(12))) {
            return response()->json($cached->output_payload);
        }

        try {
            $result = $this->geminiAIService->generateLeadInsights($lead, auth()->id());

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Lead insights generation failed.',
                'error' => app()->environment('local') ? $e->getMessage() : null,
            ], 500);
        }
    }
}