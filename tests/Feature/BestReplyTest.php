<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;
    /** @test **/
    public function a_thread_creator_may_mark_the_reply_as_the_best_reply()
    {
       $this->be(factory(User::class)->create());

       $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

       $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

       $this->assertFalse($replies[1]->isBest());

       $this->postJson(route('best-reply.store', [$replies[1]->id]));

       $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test **/
    public function on_the_owner_of_thread_should_only_make_the_thread_as_best()
    {

       $this->be(factory(User::class)->create());

       $thread = factory(Thread::class)->create();

       $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

       $this->postJson(route('best-reply.store', [$replies[1]->id]))->assertStatus(403);

       $this->assertFalse($replies[1]->fresh()->isBest());

    }
}
