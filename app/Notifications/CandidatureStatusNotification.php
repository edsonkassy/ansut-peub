<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidatureStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $status,
        public string $opportunityTitle,
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        // Canal database retire le 20/08/2026 : il exige la table
        // notifications de Laravel, qui n a jamais ete creee. La migration
        // 2025_09_10_201147 la supposait deja presente et n a cree que
        // system_notifications, utilisee par le modele SystemNotification.
        // Ce canal ne sert plus qu a l email.
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject("Mise à jour de votre candidature - PEUB")
            ->line("Votre candidature pour '{$this->opportunityTitle}' a été ".($this->status === 'accepted' ? 'acceptée' : 'rejetée').'.');

        if ($this->status === 'accepted') {
            $message->line('Félicitations ! Nous vous contacterons bientôt avec les prochaines étapes.');
        } elseif ($this->reason) {
            $message->line("Raison : {$this->reason}");
        }

        return $message
            ->action('Voir mes candidatures', url('/candidatures'))
            ->line('Continuez à postuler aux opportunités qui vous intéressent.')
            ->salutation('L\'équipe PEUB');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'status' => $this->status,
            'opportunity_title' => $this->opportunityTitle,
            'reason' => $this->reason,
            'type' => 'candidature_status'
        ];
    }
}
