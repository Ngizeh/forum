<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
use App\Rules\Recaptcha;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
       parent::setUp();

       app()->singleton(Recaptcha::class, function(){
            return \Mockery::mock(Recaptcha::class, function ($mock){
                  $mock->shouldReceive('passes')->andReturn(true);
            });
        });
    }

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

    /** @exclude-group **/
    public function an_authenticated_user_can_create_a_thread()
    {
        $this->withoutExceptionHandling();

        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->make();

        $response = $this->post(route("threads"), $thread->toArray()+['g-recaptcha-response' => 'token']);

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

    /** @exclude-group **/
    public function recapture_is_required_for_the_thread()
    {
        unset(app()[Recaptcha::class]);

        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->make(['g-recaptcha-response' => 'test']);

        $response = $this->post(route('threads'), $thread->toArray());

        $response->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @exclude-group **/
    public function thread_requires_a_slug()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['title' => 'Test message']);

        $this->assertEquals($thread->fresh()->slug, 'test-message');

        $thread = $this->postJson(route('threads'), $thread->toArray()+['g-recaptcha-response' => 'token'])->json();

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
