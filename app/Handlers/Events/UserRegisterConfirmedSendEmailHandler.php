<?php

namespace App\Handlers\Events;

use Core\Auth\Events\UserRegisterConfirmedByEmailEvent;
use Core\Auth\Services\Sender\UserRegisterConfirmedByEmailSender;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegisterConfirmedSendEmailHandler
{
    private $sender;

    public function __construct(UserRegisterConfirmedByEmailSender $sender)
    {
        $this->sender = $sender;
    }

    public function handle(UserRegisterConfirmedByEmailEvent $event)
    {
        $this->sender->send(
            $event->getEntity()->getEmail()
        );
    }
}
