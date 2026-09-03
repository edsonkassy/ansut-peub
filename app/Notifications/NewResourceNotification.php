<?php

namespace App\Notifications;

use App\Models\LibraryResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewResourceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public LibraryResource $resource
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
        return (new MailMessage)
            ->subject('Nouvelle ressource disponible - PEUB')
            ->line("Une nouvelle ressource vient d'être ajoutée à la bibliothèque :")
            ->line("**{$this->resource->title}**")
            ->line("Catégorie : {$this->resource->category->name}")
            ->line($this->resource->description)
            ->action('Voir la ressource', route('bachelier.library.show', $this->resource))
            ->line('Découvrez cette nouvelle ressource et enrichissez vos connaissances.')
            ->salutation('L\'équipe PEUB');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'resource_id' => $this->resource->id,
            'resource_title' => $this->resource->title,
            'category' => $this->resource->category->name,
            'type' => 'new_resource'
        ];
    }
}
