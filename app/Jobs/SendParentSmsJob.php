<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendParentSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $recipientId
    ) {}

    public function handle()
    {
        $recipient =
            ParentCommunicationRecipient::find(
                $this->recipientId
            );

        if (!$recipient) {
            return;
        }

        if ($recipient->status === 'SENT') {
            return;
        }

        $communication =
            $recipient->communication;

        $result = SmsService::send(
            $recipient->phone,
            $recipient->message,
            $communication->template?->template_id
        );

        if ($result['success']) {

            $recipient->update([
                'status' => 'SENT',
                'sms_response' =>
                    $result['response'],
                'sent_at' => now(),
            ]);

        } else {

            $recipient->update([
                'status' => 'FAILED',
                'sms_response' =>
                    $result['response'],
            ]);
        }
    }
}