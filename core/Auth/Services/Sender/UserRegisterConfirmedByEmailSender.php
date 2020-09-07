<?php

namespace Core\Auth\Services\Sender;

use Core\Auth\Entities\User\Email;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class UserRegisterConfirmedByEmailSender
{
    public function send(Email $email)
    {
        $toEmail = $email->getValue();
        $data = [
            'email' => $toEmail,
        ];

        Mail::send(
            'emails.registration.account-confirmed',
            $data,
            function ($message) use ($toEmail) {
                /** @var Message $message */
                $message
                    ->to($toEmail)
                    ->subject('Email has been confirmed');
            }
        );
    }
}
