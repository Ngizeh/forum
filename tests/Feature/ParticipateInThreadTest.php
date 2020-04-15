<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInThreadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test **/
    public function only_authenticated_user_can_participate_in_forum()
    {
        $this->withoutExceptionHandling();

        $this->expectException(AuthenticationException::class);

        factory(Reply::class)->create();
        $this->post($this->thread->path()."/replies", []);
    }

    /** @test **/
    public function an_authenticated_user_can_participate_in_the_forum()
    {
        $this->be(factory(User::class)->create());

        $reply = factory(Reply::class)->make();

        $response = $this->post($this->thread->path()."/replies", $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $this->thread->fresh()->reply_count);
    }

    /** @test **/
    public function body_is_required_for_the_thread()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->make(['body' => null]);

        $response = $this->post('/threads', $thread->toArray());

        $response->assertSessionHasErrors('body');
    }

    /** @test **/
    public function guest_can_not_delete_a_reply()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $reply = factory(Reply::class)->create();
        $this->delete("/replies/{$reply->id}")->assertRedirect('/login');

        $this->be(factory(User::class)->create());
        $this->delete("/replies/{$reply->id}");
    }

    /** @test **/
    public function authorized_user_can_delete_a_reply()
    {
        $this->withoutExceptionHandling();

        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->create(['user_id' => auth()->id(), 'thread_id' => $thread->id]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $thread->fresh()->reply_count);
    }

    /** @test **/
    public function guest_can_not_update_a_reply()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        $reply = factory(Reply::class)->create();
        $this->patch("/replies/{$reply->id}")->assertRedirect('/login');

        $this->be(factory(User::class)->create());
        $this->patch("/replies/{$reply->id}");
    }

    /** @test **/
    public function authorized_user_can_update_a_reply()
    {
        $this->withoutExceptionHandling();

        $this->be(factory(User::class)->create());

        $reply = factory(Reply::class)->create(['user_id' => auth()->id()]);

        $this->patch("/replies/{$reply->id}", ['body' => 'changed']);

        $this->assertDatabaseHas('replies', ['body' => 'changed', 'user_id' => auth()->id()]);
    }

    /** @test * */
    public function it_can_detect_spam_in_the_reply()
    {
         $this->withoutExceptionHandling();

         $this->be(factory(User::class)->create());

         $reply = factory(Reply::class)->make([
             'body' => 'Google Customer Support',
             'user_id' => auth()->id()
         ]);

         $this->expectException(\Exception::class);

         $this->post($this->thread->path()."/replies", $reply->toArray());

    }

    /** @test **/
    public function users_may_reply_one_reply_per_minute()
    {

       $this->be(factory(User::class)->create());

       $reply = factory(Reply::class)->make([
           'body' => 'Some reply here',
       ]);

       $response = $this->post($this->thread->path().'/replies', $reply->toArray());

       $response->assertSuccessful();

       $response = $this->post($this->thread->path().'/replies', $reply->toArray());

       $response->assertStatus(429);
    }
}
