<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Core\Auth\Events\UserRegisteredByEmailEvent::class => [
            \App\Handlers\Events\UserRegisteredSendEmailHandler::class,
        ],
        \Core\Auth\Events\UserRegisterConfirmedByEmailEvent::class => [
            \App\Handlers\Events\UserRegisterConfirmedSendEmailHandler::class,
        ],
        \Core\Auth\Events\NewActivateTokenGeneratedEvent::class => [
            \App\Handlers\Events\NewActivateTokenGenerated::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
