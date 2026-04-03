<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $campaign->subject }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <div style="max-width: 700px; margin: 0 auto; padding: 20px;">
        <h2>{{ $campaign->subject }}</h2>

        <div>
            {!! nl2br(e(str_replace(
                ['{name}', '{email}', '{company}'],
                [
                    $lead->name ?? 'Valued Customer',
                    $lead->email ?? '',
                    $lead->company ?? ''
                ],
                $campaign->body
            ))) !!}
        </div>

        <p style="margin-top: 24px;">
            Regards,<br>
            {{ config('app.name') }}
        </p>
    </div>
</body>
</html>