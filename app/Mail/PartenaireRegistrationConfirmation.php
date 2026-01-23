<?php

namespace App\Mail;

use App\Models\Partenaire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartenaireRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $partenaire;
    public $otp;

    /**
     * Create a new message instance.
     */
    public function __construct(Partenaire $partenaire, string $otp)
    {
        $this->partenaire = $partenaire;
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre candidature partenaire PEUB',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.partenaire-registration-confirmation',
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

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation d\'inscription partenaire - ANSUT PEUB')
                    ->view('emails.partenaire-registration-confirmation');
    }
} 