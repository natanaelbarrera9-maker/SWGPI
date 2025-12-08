<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user_id;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $user_id)
    {
        $this->token = $token;
        $this->user_id = $user_id;
        $this->resetUrl = url('/auth/reset-password?token=' . $token . '&user_id=' . $user_id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperación de Contraseña - SWGPI',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset',
            with: [
                'token' => $this->token,
                'resetUrl' => $this->resetUrl,
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

    /**
     * Build the message (set explicit from address).
     */
    public function build()
    {
        $this->from('pruebastecnm7@gmail.com', 'Soporte SWGPI');

        // Add custom headers so we can trace the message in SendGrid activity
        $this->withSymfonyMessage(function ($message) {
            try {
                $headers = $message->getHeaders();
                $headers->addTextHeader('X-App-UserId', (string) $this->user_id);
                $headers->addTextHeader('X-App-Token', (string) $this->token);
            } catch (\Throwable $e) {
                // no-op: header addition is advisory, don't break sending
            }
        });

        return $this;
    }
}
