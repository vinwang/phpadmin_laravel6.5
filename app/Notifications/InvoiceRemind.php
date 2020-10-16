<?php

namespace App\Notifications;

use App\Models\Remind;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceRemind extends Notification
{
    // use Queueable;

    protected $remind;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Remind $remind)
    {
        $this->remind = $remind;
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
    public function toDatabase($notifiable)
    {
        return [
            'remind_id' => $this->remind->id
        ];
    }
}
