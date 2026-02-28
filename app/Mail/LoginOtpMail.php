<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public int $ttlMinutes
    ) {}

    public function build()
    {
        return $this->subject('Kode OTP Login APSone')
            ->view('emails.login_otp')
            ->with([
                'otp' => $this->otp,
                'ttlMinutes' => $this->ttlMinutes,
            ]);
    }
}
