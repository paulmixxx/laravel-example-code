<?php

namespace App\Handlers\Events;

use Core\Auth\Events\NewActivateTokenGeneratedEvent;
use Core\Auth\Services\Sender\NewActivateTokenGeneratedByEmailSender;

class NewActivateTokenGenerated
{
    private $sender;

    public function __construct(NewActivateTokenGeneratedByEmailSender $sender)
    {
        $this->sender = $sender;
    }

    public function handle(NewActivateTokenGeneratedEvent $event)
    {
        $this->sender->send(
            $event->getEntity()->getEmail(),
            $event->getEntity()->getActivateToken()
        );
    }
}
