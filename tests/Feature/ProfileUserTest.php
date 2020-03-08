<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_a_have_a_profile()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create();

        $this->get("/profile/{$user->name}")->assertSee($user->name);
    }

    /** @test **/
    public function on_profile_page_thread_can_be_seen_on_the_associated_user()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->get("/profile/". auth()->user()->name)
             ->assertSee($thread->body);
    }
}
