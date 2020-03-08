<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use App\Activity;
use Tests\TestCase;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function guest_can_not_create_a_thread()
    {
        $this->post(route('threads'))->assertRedirect(route('login'));

        $this->get('threads/create')->assertRedirect(route('login'));
    }
    /** @test **/
    public function authenticated_user_must_confirm_the_email_address()
    {
        $this->be(factory(User::class)->states('unconfirmed')->create());

        $thread = factory(Thread::class)->create();

        $response = $this->post(route('threads'), $thread->toArray());

        $response->assertRedirect(route('threads'))
                ->assertSessionHas('flash', 'You must first confirm your email address');
    }

    /** @test **/
    public function an_authenticated_user_can_create_a_thread()
    {
        $this->withoutExceptionHandling();

        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->make();

        $response = $this->post(route("threads"), $thread->toArray());

        $this->get($response->headers->get('Location'))->assertSee($thread->title)->assertSee($thread->body);
    }

    /** @test **/
    public function title_is_required_for_the_thread()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->make(['title' => null]);

        $response = $this->post(route('threads'), $thread->toArray());

        $response->assertSessionHasErrors('title');
    }

    /** @test **/
    public function body_is_required_for_the_thread()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->make(['body' => null]);

        $response = $this->post(route('threads'), $thread->toArray());

        $response->assertSessionHasErrors('body');
    }

    /** @test **/
    public function thread_requires_a_slug()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['title' => 'Test message']);

        $this->assertEquals($thread->fresh()->slug, 'test-message');

        $thread = $this->postJson(route('threads'), $thread->toArray())->json();

        $this->assertEquals("test-message-{$thread['id']}", $thread['slug']);
    }

    /** @test **/
    public function unauthorized_user_can_not_delete_a_thread()
    {
        $thread = factory(Thread::class)->create();

        $this->delete($thread->path())->assertRedirect('/login');

        $this->be(factory(User::class)->create());

        $this->delete($thread->path())->assertForbidden();
    }

    /** @test **/
    public function authorized_user_can_delete_a_thread()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $this->delete($thread->path());

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, Activity::count());
    }

    /** @test **/
    public function channel_is_required_and_an_existing_one_for_the_thread()
    {
        $this->be(factory(User::class)->create());

        factory(Channel::class, 2)->create();
        $thread = factory(Thread::class)->make(['channel_id' => null]);
        $this->post(route('threads'), $thread->toArray())->assertSessionHasErrors('channel_id');

        $thread = factory(Thread::class)->make(['channel_id' => 999]);
        $this->post(route('threads'), $thread->toArray())->assertSessionHasErrors('channel_id');
    }

}
