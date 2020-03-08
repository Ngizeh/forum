<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_confirmation_is_sent_upon_registration()
    {
       Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

       Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test **/
    public function user_can_full_confirm_the_registration()
    {
       $this->post(route('register'), [
           'name' => 'John',
           'email' => 'john@mail.com',
           'password' => 'password',
           'password_confirmation' => 'password'
       ]);

       $user = User::whereName('John')->first();

       $this->assertFalse($user->confirmed);
       $this->assertNotNull($user->confirmation_token);

       $this->get(route('confirm', ['token' => $user->confirmation_token]))->assertRedirect(route('threads'));

       tap($user->fresh(), function($user) {
           $this->assertTrue($user->confirmed);
           $this->assertNull($user->confirmation_token);
       });

    }

    /** @test **/
    public function must_confirm_with_a_valid_token()
    {
        $response  = $this->get(route('confirm', ['token' => 'invalid-confirmation-token']));

        $response->assertRedirect(route('threads'))->assertSessionHas('flash', 'Unknown Token');
    }
}
