<?php

namespace Core;

trait EventsTrait
{
    private $events = [];

    public function recordEvent($event)
    {
        $this->events[] = $event;
    }

    public function releaseEvents()
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
