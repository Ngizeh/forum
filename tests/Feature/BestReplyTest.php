<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_thread_creator_may_mark_the_reply_as_the_best_reply()
    {
       $this->be(User::factory()->create());

       $thread = Thread::factory()->create(['user_id' => auth()->id()]);

       $replies = Reply::factory()->times(2)->create(['thread_id' => $thread->id]);

       $this->assertFalse($replies[1]->isBest());

       $this->postJson(route('best-reply.store', [$replies[1]->id]));

       $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test **/
    public function on_the_owner_of_thread_should_only_make_the_thread_as_best()
    {

       $this->be(User::factory()->create());

       $thread = Thread::factory()->create();

       $replies = Reply::factory()->times(2)->create(['thread_id' => $thread->id]);

       $this->postJson(route('best-reply.store', [$replies[1]->id]))->assertStatus(403);

       $this->assertFalse($replies[1]->fresh()->isBest());

    }

    /**
     * @codeCoverageIgnore
     */
    public function if_the_best_reply_is_deleted_the_thread_reply_should_reflect_that()
    {

        $this->be(User::factory()->create());

        $reply = Reply::factory()->create(['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $this->deleteJson(route('reply.destroy', $reply));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
