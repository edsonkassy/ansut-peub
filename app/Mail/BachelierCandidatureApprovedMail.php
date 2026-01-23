<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Bachelier;
use App\Models\User;

class BachelierCandidatureApprovedMail extends Mailable // Synchrone - envoi immédiat
{
    use Queueable, SerializesModels;

    public $bachelier;
    public $user;
    public $approvedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(Bachelier $bachelier, User $user, ?User $approvedBy = null)
    {
        $this->bachelier = $bachelier;
        $this->user = $user;
        $this->approvedBy = $approvedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Félicitations ! Votre candidature PEUB est acceptée',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bachelier-candidature-approved',
            with: [
                'bachelier' => $this->bachelier,
                'user' => $this->user,
                'approvedBy' => $this->approvedBy,
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

