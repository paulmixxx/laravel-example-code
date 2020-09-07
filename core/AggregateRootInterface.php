<?php

namespace Core;

interface AggregateRootInterface
{
    public function releaseEvents();
}
