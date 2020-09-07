<?php

namespace Tests\Feature\Http\Api\v1\Auth\Registration;

use Core\Auth\Commands\Registration\ByEmail\GenerateActivateToken;
use Core\Auth\Commands\Registration\ByEmail\Confirm;
use Core\Auth\Commands\Registration\ByEmail\Request;
use Core\Auth\Events\UserRegisteredByEmailEvent;
use Core\Auth\Repositories\UserRepository;
use Core\EventDispatcher;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SendNewActivateTokenTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @throws Exception
     */
    public function testGenerateNewToken()
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

        $command = new GenerateActivateToken\Command();
        $command->email = $commandRequest->email;

        $handler = new GenerateActivateToken\Handler(
            new UserRepository(),
            new EventDispatcher()
        );
        $handler->handle($command);

        $response = $this->postJson(route('api.registration.send-new-token', ['email' => $command->email]));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
