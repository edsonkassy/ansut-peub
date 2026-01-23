<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $user;
    public $isWelcome;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, User $user, bool $isWelcome = false)
    {
        $this->otp = $otp;
        $this->user = $user;
        $this->isWelcome = $isWelcome;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isWelcome
            ? 'Bienvenue sur PEUB - Votre code de vérification'
            : 'Votre code de connexion PEUB';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->isWelcome ? 'emails.otp-welcome' : 'emails.otp-login';

        return new Content(
            view: $view,
            with: [
                'otp' => $this->otp,
                'user' => $this->user,
                'isWelcome' => $this->isWelcome
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