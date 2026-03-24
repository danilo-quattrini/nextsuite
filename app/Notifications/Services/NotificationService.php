<?php

namespace App\Notifications\Services;

use App\Notifications\GenericNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Notify a notifiable model (User, etc.) through given channels.
     *
     * From a
     * @param  object  $notifiable
     * @param  object  $notifiable  it will send a
     * notification object of type GenericNotification, where
     * it will include a * * * @param  string  $subject  a
     * @param  string  $message  and in case it will be a
     * @param  string|null  $actionText  with its
     * @param  string|null  $actionUrl
     * @param  array  $channels
     * @return bool
     */
    public function send(
        object  $notifiable,
        string  $subject,
        string  $message,
        ?string $actionText = null,
        ?string $actionUrl  = null,
        array   $channels   = ['mail'],
    ): bool {
        try {
            $notifiable->notify(new GenericNotification(
                subject:    $subject,
                message:    $message,
                actionText: $actionText,
                actionUrl:  $actionUrl,
                channels:   $channels,
            ));
            return true;
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'error' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Send it to multiple notification at once (on-demand).
     *
     * From a set of objects that are notifiables, which
     * means they are use the trait Notifiable, they are
     * going to receive a @param  string  $subject and
     * a @param string $message for a specific channel
     * @param  array  $channels, all from the same type.
     */
    public function sendBulk(
        iterable $notifiables,
        string   $subject,
        string   $message,
        array    $channels = ['mail'],
    ): void {
        Notification::send($notifiables, new GenericNotification(
            subject:  $subject,
            message:  $message,
            channels: $channels,
        ));
    }

    // --- Convenience wrappers ---
    public function sendWelcome(object $user): bool
    {
        return $this->send(
            notifiable: $user,
            subject:    'Welcome to ' . config('app.name'),
            message:    'Thank you for joining us. Click below to get started.',
            actionText: 'Get Started',
            actionUrl:  route('dashboard'),
            channels:   ['mail', 'database'],  // email + store in DB
        );
    }
}