<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Dotation;
use App\Models\Bachelier;

class DotationAttributedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dotation;
    public $bachelier;

    /**
     * Create a new message instance.
     */
    public function __construct(Dotation $dotation, Bachelier $bachelier)
    {
        $this->dotation = $dotation;
        $this->bachelier = $bachelier;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Attribution de dotation PEUB - ' . $this->dotation->inventaire->nom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dotation-attributed',
            with: [
                'dotation' => $this->dotation,
                'bachelier' => $this->bachelier,
                'inventaire' => $this->dotation->inventaire,
                'fournisseur' => $this->dotation->inventaire->fournisseur,
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
