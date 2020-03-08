<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ThreadWasUpdated extends Notification
{
    use Queueable;

    public $thread, $reply;

    public function __construct($thread, $reply)
    {
        $this->reply = $reply;
        $this->thread = $thread;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->reply->owner->name . 'replied to ' . $this->thread->title,
             'link' => $this->reply->path()
        ];
    }
}
