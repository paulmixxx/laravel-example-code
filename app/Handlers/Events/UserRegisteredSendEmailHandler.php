<?php

namespace App\Handlers\Events;

use Core\Auth\Events\UserRegisteredByEmailEvent;
use Core\Auth\Services\Sender\UserRegisterByEmailSender;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegisteredSendEmailHandler
{
    private $sender;

    public function __construct(UserRegisterByEmailSender $sender)
    {
        $this->sender = $sender;
    }

    public function handle(UserRegisteredByEmailEvent $event)
    {
        $this->sender->send(
            $event->getEntity()->getEmail(),
            $event->getEntity()->getActivateToken()
        );
    }
}
