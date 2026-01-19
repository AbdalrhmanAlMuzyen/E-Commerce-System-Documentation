<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;

    public function __construct(string $code)
    {
        $this->code=$code;
    }

    public function build()
    {
        return $this->subject("Reset Password")->html("
            <div style='font-family: Arial, sans-serif; background-color:#f4f6f8; padding:20px;'>
                <div style='max-width:500px; margin:auto; background:#ffffff; padding:30px; border-radius:8px;'>
                    
                    <h2 style='color:#1a73e8; text-align:center;'>Reset Password</h2>

                    <p style='font-size:15px; color:#333;'>
                        You requested to reset your password.
                    </p>

                    <p style='font-size:15px; color:#333;'>
                        Please use the verification code below to reset your password:
                    </p>

                    <div style='text-align:center; margin:30px 0;'>
                        <span style='font-size:28px; letter-spacing:4px; font-weight:bold; color:#000;'>
                            {$this->code}
                        </span>
                    </div>

                    <p style='font-size:14px; color:#666;'>
                        This code will expire in <strong>30 minutes</strong>.
                        <br>
                        If you did not request a password reset, please ignore this email.
                    </p>

                </div>
            </div>
        ");
        
    }
}
