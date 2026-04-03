<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIContentService
{
    protected function callGemini(string $prompt): string
    {
        $apiKey = env('GEMINI_API_KEY');

        if (! $apiKey) {
            throw new \Exception('GEMINI_API_KEY missing in .env');
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

        $response = Http::timeout(60)
            ->acceptJson()
            ->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt,
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'responseMimeType' => 'application/json',
                ],
            ]);

        if ($response->failed()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception('Gemini API error: ' . $response->status() . ' - ' . $response->body());
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

        if (! is_string($text) || trim($text) === '') {
            Log::error('Gemini empty response', [
                'json' => $response->json(),
            ]);

            throw new \Exception('Gemini returned empty response');
        }

        return trim($text);
    }

    protected function parseJson(string $text): array
    {
        $decoded = json_decode($text, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{.*\}/s', $text, $matches)) {
            $decoded = json_decode($matches[0], true);

            if (is_array($decoded)) {
                return $decoded;
            }
        }

        throw new \Exception('Invalid JSON returned by Gemini: ' . $text);
    }

    public function generateEmailContent(array $data): array
    {
        $prompt = <<<PROMPT
You are an expert B2B sales and email marketing assistant.

Generate a professional outreach email campaign for the following context:

Campaign Goal: {$data['goal']}
Target Audience: {$data['audience']}
Tone: {$data['tone']}
Offer / Service: {$data['offer']}

Return ONLY valid JSON in this exact format:
{
  "subject": "string",
  "body": "string"
}

Rules:
- Keep the subject concise and compelling
- Body should be professional and easy to read
- Use placeholders like {name} and {company} where appropriate
- No markdown
- No explanation
- No extra text
PROMPT;

        $text = $this->callGemini($prompt);
        $decoded = $this->parseJson($text);

        return [
            'subject' => $decoded['subject'] ?? 'AI-generated subject',
            'body' => $decoded['body'] ?? '',
        ];
    }

    public function generateLeadInsights(Lead $lead): array
    {
        $prompt = <<<PROMPT
You are a CRM sales assistant.

Analyze this lead and return ONLY valid JSON.

Lead Data:
Name: {$lead->name}
Email: {$lead->email}
Phone: {$lead->phone}
Company: {$lead->company}
Source: {$lead->source}
Subject: {$lead->subject}
Message: {$lead->message}
Current Status: {$lead->status}
Priority: {$lead->priority}

Return JSON in this exact format:
{
  "summary": "short summary",
  "score": 0,
  "next_action": "recommended next action"
}

Rules:
- score must be from 0 to 100
- summary should be concise
- next_action should be practical
- No markdown
- No explanation
- No extra text
PROMPT;

        $text = $this->callGemini($prompt);
        $decoded = $this->parseJson($text);

        return [
            'summary' => $decoded['summary'] ?? '',
            'score' => (int) ($decoded['score'] ?? 50),
            'next_action' => $decoded['next_action'] ?? '',
        ];
    }
}