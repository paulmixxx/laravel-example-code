<?php

namespace Tests\Feature\Http\Api\v1\Auth\Registration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RequestTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateSuccess()
    {
        Event::fake();

        $response = $this->postJson(
            route('api.registration.request'),
            [
                'email' => 'new.email@mail.com',
                'password' => 'secret_password',
                'password_confirmation' => 'secret_password',
                'firstName' => 'Ivan',
            ]
        );

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);
    }
}
