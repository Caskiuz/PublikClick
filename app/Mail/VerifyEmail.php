<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->verificationUrl = url('/verify-email/' . base64_encode($user->email) . '/' . md5($user->email . $user->created_at));
    }

    public function build()
    {
        return $this->subject('Verifica tu email - PubliClick')
                   ->view('emails.verify')
                   ->with([
                       'user' => $this->user,
                       'verificationUrl' => $this->verificationUrl
                   ]);
    }
}