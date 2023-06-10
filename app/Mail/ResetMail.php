<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $greeting,
        public string $intructions,
        public string $buttonText,
        public string $hint,
        public string $any_problems,
        public string $regards,
        public string $url,
    ) {

    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Mail',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'auth.feedback',
            with: [
                'greeting' => $this->greeting,
                'thank_you' => $this->intructions,
                'buttonText' => $this->buttonText,
                'hint' => $this->hint,
                'any_problems' => $this->any_problems,
                'regards' => $this->regards,
                'url'=> $this->url,
            ],
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
