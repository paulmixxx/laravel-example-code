<?php

namespace Tests\Feature\Http\Api\v1\Auth\Registration;

use Core\Auth\Commands\Registration\ByEmail\Request;
use Core\Auth\Events\UserRegisteredByEmailEvent;
use Core\Auth\Repositories\UserRepository;
use Core\EventDispatcher;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ConfirmTest extends TestCase
{
    use DatabaseMigrations;

    public function testConfirmSuccess()
    {
        Event::fake();

        $this->expectsEvents(UserRegisteredByEmailEvent::class);

        $commandRequest = new Request\Command();
        $handlerRequest = new Request\Handler(
            new UserRepository(),
            new EventDispatcher()
        );

        $commandRequest->firstName = 'Ivan';
        $commandRequest->email = 'new.email@test.com';
        $commandRequest->password = 'secret_pass';

        $handlerRequest->handle($commandRequest);

        $activateToken = null;
        foreach ($this->firedEvents as $event) {
            if ($event instanceof UserRegisteredByEmailEvent) {
                $activateToken = $event->getEntity()->getActivateToken();
            }
        }

        $response = $this->getJson(route('api.registration.confirm', ['token' => $activateToken->getValue()]));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
