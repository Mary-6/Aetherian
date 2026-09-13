<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public static function send(string $to, string $message): bool
    {
        $sid = config('services.twilio.sid') ?: env('TWILIO_SID');
        $token = config('services.twilio.token') ?: env('TWILIO_AUTH_TOKEN');
        $from = config('services.twilio.from') ?: env('TWILIO_FROM');

        if (! $sid || ! $token || ! $from || ! $to) {
            Log::info('SMS not sent (missing config)', ['to' => $to, 'message' => $message]);

            return false;
        }

        try {
            $client = new Client($sid, $token);
            $client->messages->create($to, [
                'from' => $from,
                'body' => $message,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('SMS send failed: ' . $e->getMessage(), ['to' => $to]);

            return false;
        }
    }

    public static function adminPhone(): ?string
    {
        return config('services.twilio.admin_phone') ?: env('ADMIN_PHONE');
    }
}
