<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($user->email));
    }

    public function build()
    {
        return $this->subject('Recuperar Contraseña - PubliClick')
                   ->view('emails.reset-password')
                   ->with([
                       'user' => $this->user,
                       'resetUrl' => $this->resetUrl
                   ]);
    }
}