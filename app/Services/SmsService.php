<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public static function send($phone, $message)
    {
        try {

            $response = Http::get(config('services.sms.base_url'), [
                'username'     => config('services.sms.username'),
                'api_password' => config('services.sms.password'),
                'sender'       => config('services.sms.sender'),
                'to'           => $phone,
                'message'      => $message,
                'priority'     => config('services.sms.priority'),
                'e_id'         => config('services.sms.e_id'),
                't_id'         => config('services.sms.t_id'),
            ]);

            Log::info('SMS Sent', [
                'phone' => $phone,
                'response' => $response->body()
            ]);

            return true;

        } catch (\Exception $e) {

            Log::error('SMS Failed', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
