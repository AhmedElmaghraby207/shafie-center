<?php

namespace App\Notifications;

use App\Channels\FireBaseChannel;
use App\NotificationTemplate;
use Illuminate\Support\Facades\Config;

class P_Walk extends _BaseNotification
{
    public $subject;
    public $content;

    public function __construct()
    {
        parent::__construct('P_Walk');
        $this->template = NotificationTemplate::where('name', $this->template_Name)->first();

        $this->subject = $this->template->subject;
        $this->content = $this->template->template;
    }

    public function via($notifiable)
    {
        return [
            'database',
            FireBaseChannel::class
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'subject' => $this->subject,
            'content' => $this->content,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'subject' => $this->subject,
            'content' => $this->content,
        ];
    }

    public function toSubject($data)
    {
        return $this->subject;
    }

    public function toString($data)
    {
        return $this->content;
    }

    public function toObject($data)
    {
        $res = $this->object;
        $res['subject'] = $this->subject;
        $res['content'] = $this->content;
        $res['entity_id'] = Config::get('constants.ENTITY_ID_Walk');
//        $res['popup'] = $this->template->is_popup;
//        $res['popup_image'] = Setting::where('key', 'announcement_popup_image_url')->first()->value;
//        $res['popup_image'] = $this->template->popup_image;
//        $res['thumbnail'] = Setting::where('key', 'announcement_image_url')->first()->value;
        return $res;
    }
}
