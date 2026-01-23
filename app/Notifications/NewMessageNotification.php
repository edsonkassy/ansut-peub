<?php

namespace App\Notifications;

use App\Models\BachelierMessage;
use App\Models\BachelierConversation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BachelierMessage $message,
        public BachelierConversation $conversation,
        public string $senderName
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $preview = substr($this->message->content, 0, 100);
        if (strlen($this->message->content) > 100) {
            $preview .= '...';
        }

        return (new MailMessage)
            ->subject("Nouveau message de {$this->senderName} - PEUB")
            ->line("Vous avez reçu un nouveau message de **{$this->senderName}** :")
            ->line("\"$preview\"")
            ->action('Répondre au message', route('bachelier.inbox.show', $this->conversation))
            ->line('Connectez-vous pour voir le message complet et répondre.')
            ->salutation('L\'équipe PEUB');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'conversation_id' => $this->conversation->id,
            'sender_name' => $this->senderName,
            'message_preview' => substr($this->message->content, 0, 100),
            'type' => 'new_message'
        ];
    }
}
