<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ImplementorCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $username;
    public $email;
    public $password;

    public function __construct($name, $username, $email, $password)
    {
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to TURO-MOKO - Your Account Credentials',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.implementor_credentials', // We will create this view next
        );
    }
}