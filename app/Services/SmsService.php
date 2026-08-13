<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public static function send(
        string $phone,
        string $message,
        ?string $templateId = null
    ): array {

        Log::info('SMS SERVICE CALLED', [
            'phone' => $phone,
            'template_id' => $templateId,
        ]);

        try {

            $templateId = $templateId ?: config('services.sms.t_id');

            $params = [
                'username' => config('services.sms.username'),
                'api_password' => config('services.sms.password'),
                'sender' => config('services.sms.sender'),
                'to' => $phone,
                'message' => $message,
                'priority' => config('services.sms.priority'),
                'e_id' => config('services.sms.e_id'),
                't_id' => $templateId,
            ];

            Log::info('Ideal SMS Request', [
                'phone' => $phone,
                'template_id' => $templateId,
                'entity_id' => config('services.sms.e_id'),
                'sender' => config('services.sms.sender'),
                'message' => $message,
            ]);

            $response = Http::timeout(20)->get(
                config('services.sms.base_url'),
                $params
            );

            $body = trim($response->body());

            Log::info('Ideal SMS Response', [
                'phone' => $phone,
                'http_status' => $response->status(),
                'response' => $body,
            ]);

            return [
                'success' => $response->successful(),
                'response' => $body,
            ];

        } catch (\Throwable $e) {

            Log::error('Ideal SMS Exception', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'response' => $e->getMessage(),
            ];
        }
    }
}