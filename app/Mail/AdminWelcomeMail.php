<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AdminWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;
    public $createdBy;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $otp, ?User $createdBy = null)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->createdBy = $createdBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue dans l\'administration PEUB - Accès administrateur',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-welcome',
            with: [
                'user' => $this->user,
                'otp' => $this->otp,
                'createdBy' => $this->createdBy,
            ],
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