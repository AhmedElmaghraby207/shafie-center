<?php

namespace App\Notifications;

use App\Channels\FireBaseChannel;
use App\NotificationTemplate;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;

class P_Announcement extends _BaseNotification
{
    public $title;
    public $content;

    public function __construct($title = null, $content = null)
    {
        parent::__construct('P_Announcement');

        $this->title = $title;
        $this->content = $content;
        $this->template = NotificationTemplate::where('name', $this->template_Name)->first();
    }

    public function via($notifiable)
    {
        return [
            //'mail',
            'database',
            FireBaseChannel::class
        ];
    }

    public function toMail($notifiable)
    {
        $url = '/';
        return (new MailMessage)
            ->subject('')
            ->markdown('emails.' . $this->template, ['url' => $url]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

    public function toSubject($data)
    {
        $template = $this->template;
        if (empty($template))
            return '';
        return view(['template' => $template->subject], [
            "subject" => $this->title,
        ])->render();
    }

    public function toString($data)
    {
        $template = $this->template;
        if (empty($template))
            return '';

        $dataObject = $this->reloadDataObject($data);
        return view(['template' => $template->template], [
            "content" => $dataObject['content']
        ])->render();
    }

    public function reloadDataObject($data)
    {
        if ($this->dataObject == []) {
            $this->dataObject['title'] = $data['title'];
            $this->dataObject['content'] = $data['content'];
        }
        return $this->dataObject;
    }

    public function toObject($data)
    {
        $res = $this->object;
        $dataObject = $this->reloadDataObject($data);
        $res['title'] = $dataObject['title'];
        $res['entity_id'] = Config::get('constants.SOCIAL_ENTITY_ID_Announcement');
        $res['message'] = $dataObject['content'];
//        $res['popup'] = $this->template->is_popup;
//        $res['popup_image'] = Setting::where('key', 'announcement_popup_image_url')->first()->value;
//        $res['popup_image'] = $this->template->popup_image;
//        $res['thumbnail'] = Setting::where('key', 'announcement_image_url')->first()->value;
        return $res;
    }
}
