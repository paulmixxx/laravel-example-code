<?php

namespace Tests\Feature\Commands\Registration\ByEmail;

use Carbon\Carbon;
use Core\Auth\Commands\Registration\ByEmail\GenerateActivateToken;
use Core\Auth\Commands\Registration\ByEmail\Request;
use Core\Auth\Commands\Registration\ByEmail\Confirm;
use Core\Auth\Entities\User\User;
use Core\Auth\Events\NewActivateTokenGeneratedEvent;
use Core\Auth\Events\UserRegisterConfirmedByEmailEvent;
use Core\Auth\Events\UserRegisteredByEmailEvent;
use Core\Auth\Exceptions\UserAlreadyActivated;
use Core\Auth\Exceptions\UserNotFoundException;
use Core\Auth\Repositories\UserRepository;
use Core\EventDispatcher;
use Event;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenerateNewTokenTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @throws Exception
     */
    public function testGenerateNewTokenSuccess()
    {
        Event::fake();

        $this->expectsEvents(NewActivateTokenGeneratedEvent::class);

        $user = $this->createUserActivateToken();
        $user = $this->setExpiredToken($user);

        $command = new GenerateActivateToken\Command();
        $command->email = $user->getEmail()->getValue();

        $handler = new GenerateActivateToken\Handler(
            new UserRepository(),
            new EventDispatcher()
        );
        $handler->handle($command);
    }

    /**
     * @throws Exception
     */
    public function testUserNotFound()
    {
        Event::fake();

        $this->expectException(UserNotFoundException::class);

        $command = new GenerateActivateToken\Command();
        $command->email = 'not.exist.user@test.com';

        $handler = new GenerateActivateToken\Handler(
            new UserRepository(),
            new EventDispatcher()
        );
        $handler->handle($command);
    }

    /**
     * @throws Exception
     */
    public function testUserAlreadyActivated()
    {
        Event::fake();

        $this->expectsEvents(UserRegisterConfirmedByEmailEvent::class);

        $this->expectException(UserAlreadyActivated::class);

        $user = $this->createUserActivateToken();
        $activateToken = $user->getActivateToken();
        $email = $user->getEmail()->getValue();

        $commandConfirm = new Confirm\Command();
        $commandConfirm->token = $activateToken->getValue();

        $handlerConfirm = new Confirm\Handler(
            new UserRepository(),
            new EventDispatcher()
        );
        $handlerConfirm->handle($commandConfirm);

        $command = new GenerateActivateToken\Command();
        $command->email = $email;

        $handler = new GenerateActivateToken\Handler(
            new UserRepository(),
            new EventDispatcher()
        );
        $handler->handle($command);
    }

    /**
     * @throws Exception
     */
    public function createUserActivateToken()
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

        $user = null;
        foreach ($this->firedEvents as $event) {
            if ($event instanceof UserRegisteredByEmailEvent) {
                $user = $event->getEntity();
            }
        }

        return $user;
    }

    public function setExpiredToken(User $user)
    {
        $activateTokenModel =
            \App\User::where(
                'code',
                '=',
                $user->getCode()->getValue()
            )->first();

        if (null !== $activateTokenModel) {
            $activateTokenModel
                ->activateToken()
                ->update([
                    'expire_time' => (new Carbon($activateTokenModel->expire_time))->subHours(1)
                 ]);
        }

        return $user;
    }
}
