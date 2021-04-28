<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use App\Reputation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReputationTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function user_earns_points_when_they_create_a_thread()
    {
        $thread = Thread::factory()->create();

        $this->assertEquals(Reputation::THREAD_CREATED, $thread->creator->reputation);
    }

     /** @test **/
    public function user_looses_points_when_they_delete_a_thread()
    {
        $this->be(User::factory()->create());

        $thread = Thread::factory()->create(['user_id' => auth()->id()]);

        $this->delete($thread->path());

        $this->assertEquals(0, $thread->creator->fresh()->reputation);
    }

      /** @test **/
    public function user_looses_points_when_a_reply_to_a_thread_is_deleted()
    {
        $this->be(User::factory()->create());

        $reply = Reply::factory()->create(['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}");

        $this->assertEquals(0, $reply->owner->fresh()->reputation);
    }

     /** @test **/
    public function user_earn_points_when_a_reply_to_a_thread_is_favorited()
    {
        $this->be(User::factory()->create());

        $reply = Reply::factory()->create(['user_id' => auth()->id()]);

        $this->post("/replies/{$reply->id}/favorites");

        $totals = Reputation::REPLY_CREATED + Reputation::REPLY_FAVORITED;

        $this->assertEquals($totals, $reply->owner->fresh()->reputation);
    }

     /** @test **/
    public function user_looses_points_when_a_reply_to_a_thread_is_unfavorited()
    {
        $this->be(User::factory()->create());

        $reply = Reply::factory()->create(['user_id' => auth()->id()]);

        $this->post("/replies/{$reply->id}/favorites");

        $this->delete("/replies/{$reply->id}/favorites");

        $totals = Reputation::REPLY_CREATED + Reputation::REPLY_FAVORITED - Reputation::REPLY_FAVORITED;

        $this->assertEquals($totals, $reply->owner->fresh()->reputation);
    }


      /** @test **/
    public function user_earns_points_when_they_reply_is_marked_as_best()
    {
        $thread = Thread::factory()->create();

        $reply = $thread->addReply([
            'body' => 'I have something to say',
            'user_id' => User::factory()->create()->id
        ]);

        $thread->markBestReply($reply);

        $totals = Reputation::REPLY_CREATED + Reputation::REPLY_MARKED_AS_BEST;

        $this->assertEquals($totals, $reply->owner->reputation);
    }
}
