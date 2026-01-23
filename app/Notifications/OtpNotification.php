<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $otp,
        public string $action = 'vérification'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Code de vérification - PEUB')
            ->line("Votre code de {$this->action} est :")
            ->line("**{$this->otp}**")
            ->line("Ce code est valable pendant 10 minutes.")
            ->line("Si vous n'avez pas demandé ce code, ignorez ce message.")
            ->salutation('L\'équipe PEUB');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
            'action' => $this->action,
            'type' => 'otp'
        ];
    }
}
