<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\OrderConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    public function handle(OrderPlaced $event): void
    {
        $user = $event->order->user;

        if (!$user || !$user->email) {
            return;
        }

        Mail::to($user->email)->send(new OrderConfirmationMail($event->order));
    }
}
