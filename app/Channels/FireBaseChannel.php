<?php

namespace App\Channels;

use App\Helpers\FCMHelper;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;

class FireBaseChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        //\Log::info('==== FireBaseChannel - Send ===');
        $subject = $notification->toSubject($notifiable);
        $data = $notification->toArray($notifiable);
        $message = $notification->toString($data);
        $created_at = Carbon::now();
        $date = $created_at->toFormattedDateString();
        $is_read = false;

        $notification->object["message"] = $message;
        $notification->object["date"] = $date;
        $notification->object["is_read"] = $is_read;

        $data = $notification->toObject($data);
        $tokens = $notifiable->firebase_tokens();
        $badge = $notifiable->get_new_notifications_count();

        if (sizeof($tokens) > 0) {
            FCMHelper::Send_Downstream_Message_Multiple($tokens, $subject, $message, ["data" => $data], $badge);
        }

    }
}