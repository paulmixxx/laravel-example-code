<?php

namespace Core;

use Illuminate\Events\Dispatcher;

class EventDispatcher extends Dispatcher implements EventDispatcherInterface
{
    public function dispatchAll($events)
    {
        foreach ($events as $event) {
            event($event);
        }
    }
}
