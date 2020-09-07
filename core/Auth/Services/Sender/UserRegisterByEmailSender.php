<?php

namespace Core\Auth\Services\Sender;

use Core\Auth\Entities\User\ActivateToken;
use Core\Auth\Entities\User\Email;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class UserRegisterByEmailSender
{
    public function send(Email $email, ActivateToken $token)
    {
        $toEmail = $email->getValue();
        $data = [
            'email' => $toEmail,
            'token' => $token->getValue(),
        ];

        Mail::send(
            'emails.registration.registration-information',
            $data,
            function ($message) use ($toEmail) {
                /** @var Message $message */
                $message
                    ->to($toEmail)
                    ->subject('Registration information');
            }
        );
    }
}
