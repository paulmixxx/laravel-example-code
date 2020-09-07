<?php

namespace Tests\Feature\Commands\Registration\ByEmail;

use Carbon\Carbon;
use Core\Auth\Commands\Registration\ByEmail\Request;
use Core\Auth\Commands\Registration\ByEmail\Confirm;
use Core\Auth\Events\UserRegisterConfirmedByEmailEvent;
use Core\Auth\Events\UserRegisteredByEmailEvent;
use Core\Auth\Exceptions\ActivateTokenIsExpired;
use Core\Auth\Exceptions\NotFoundActivateToken;
use Core\Auth\Repositories\UserRepository;
use Core\Auth\Services\ActivateTokenizer;
use Core\EventDispatcher;
use DateTimeImmutable;
use Event;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ConfirmTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @throws Exception
     */
    public function testSuccessActivateUser()
    {
        Event::fake();

        $activateToken = $this->createUserActivateToken()->getActivateToken();

        $this->expectsEvents(UserRegisterConfirmedByEmailEvent::class);

        $commandConfirm = new Confirm\Command();
        $commandConfirm->token = $activateToken->getValue();

        $handlerConfirm = new Confirm\Handler(
            new UserRepository(),
            new EventDispatcher()
        );
        $handlerConfirm->handle($commandConfirm);
    }

    /**
     * @throws Exception
     */
    public function testActivateTokenNotFound()
    {
        Event::fake();

        $this->expectException(NotFoundActivateToken::class);

        $commandConfirm = new Confirm\Command();
        $commandConfirm->token = ActivateTokenizer::gen(new DateTimeImmutable())->getValue();

        $handlerConfirm = new Confirm\Handler(
            new UserRepository(),
            new EventDispatcher()
        );
        $handlerConfirm->handle($commandConfirm);
    }

    /**
     * @throws Exception
     */
    public function testActivateTokenIsExpired()
    {
        Event::fake();

        $this->expectException(ActivateTokenIsExpired::class);

        $user = $this->createUserActivateToken();
        $activateTokenModel =
            \App\User::where(
                'code',
                '=',
                $user->getCode()->getValue()
            )->first();


        if (null !== $activateTokenModel) {
            $activateTokenModel =
                $activateTokenModel
                    ->activateToken()
                    ->update([
                        'expire_time' => (new Carbon($activateTokenModel->expire_time))->subHours(1)
                     ]);
        }

        $commandConfirm = new Confirm\Command();
        $commandConfirm->token = $user->getActivateToken()->getValue();

        $handlerConfirm = new Confirm\Handler(
            new UserRepository(),
            new EventDispatcher()
        );
        $handlerConfirm->handle($commandConfirm);
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
}
