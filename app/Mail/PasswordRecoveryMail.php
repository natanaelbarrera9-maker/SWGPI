<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordRecoveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $userEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $userEmail)
    {
        $this->token = $token;
        $this->userEmail = $userEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'pruebastecnm7@gmail.com',
            subject: 'Recuperación de Contraseña - SWGPI',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_recovery',
            with: [
                'token' => $this->token,
                'userEmail' => $this->userEmail,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
