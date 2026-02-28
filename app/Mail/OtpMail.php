<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly object $user,
        public readonly string $otp,
    ) {}

    public function build()
    {
        return $this->subject('Kode OTP Login APSone')
            ->view('auth.mail-message')
            ->with([
                'user' => $this->user,
                'otp'  => $this->otp,
            ]);
    }
}
