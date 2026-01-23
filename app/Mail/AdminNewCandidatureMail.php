<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Bachelier;
use App\Models\User;

class AdminNewCandidatureMail extends Mailable // Synchrone - envoi immédiat
{
    use Queueable, SerializesModels;

    public $bachelier;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Bachelier $bachelier, User $user)
    {
        $this->bachelier = $bachelier;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Nouvelle candidature PEUB en attente de validation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-candidature',
            with: [
                'bachelier' => $this->bachelier,
                'user' => $this->user,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

