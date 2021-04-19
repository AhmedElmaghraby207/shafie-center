<?php

namespace App\Notifications;

use App\Channels\FireBaseChannel;
use App\NotificationTemplate;
use App\Setting;
use Illuminate\Support\Facades\Config;

class P_Announcement extends _BaseNotification
{
    public $subject;
    public $content;

    public function __construct($subject = null, $content = null)
    {
        parent::__construct('P_Announcement');

        $this->subject = $subject;
        $this->content = $content;
        $this->template = NotificationTemplate::where('name', $this->template_Name)->first();
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
        $template = $this->template;
        if (empty($template))
            return '';

        $dataObject = $this->reloadDataObject($data);

        $res = view('template.P_Announcement_subject', [
            "subject" => $dataObject['subject'],
        ])->render();

        return trim($res);
    }

    public function toString($data)
    {
        $template = $this->template;
        if (empty($template))
            return '';

        $dataObject = $this->reloadDataObject($data);

        $res = view('template.P_Announcement_content', [
            "content" => $dataObject['content']
        ])->render();

        return trim($res);
    }

    public function reloadDataObject($data)
    {
        $data = (object)$data;
        if ($this->dataObject == []) {
            $this->dataObject['subject'] = $data->subject;
            $this->dataObject['content'] = $data->content;
        }
        return $this->dataObject;
    }

    public function toObject($data)
    {
        $dataObject = $this->reloadDataObject($data);

        $res = $this->object;
        $res['subject'] = $dataObject['subject'];
        $res['content'] = $dataObject['content'];
        $res['entity_id'] = Config::get('constants.ENTITY_ID_Announcement');
        $res['thumbnail'] = url(Setting::where('key', 'announcement_notification_image')->first()->value);
        return $res;
    }
}
