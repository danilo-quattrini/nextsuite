<?php

namespace App\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GenericNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $subject,
        protected string  $message,
        protected ?string $actionText = null,
        protected ?string $actionUrl  = null,
        protected array   $channels   = ['mail']
    )
    {
    }

    /**
     * Which channels to use — driven by whatever was passed in.
     */
    public function via($notifiable): array
    {
        return $this->channels;
    }

    /**
     * The mail representation.
     */
    public function toMail($notifiable): MailMessage
    {
        $mail =  (new MailMessage)
            ->subject($this->subject)
            ->line($this->message);

        if($this->actionText && $this->actionUrl){
            $mail->action($this->actionText, $this->actionUrl);
        }

        return $mail;
    }

    /**
     * The database representation (stored in notifications table).
     */
    public function toDatabase($notifiable): array
    {
        return [
            'subject'     => $this->subject,
            'message'     => $this->message,
            'action_text' => $this->actionText,
            'action_url'  => $this->actionUrl,
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
