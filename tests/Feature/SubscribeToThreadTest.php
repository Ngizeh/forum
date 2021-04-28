<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_subscribe_to_a_thread()
    {
        $this->be(User::factory()->create());

        $thread = Thread::factory()->create(['user_id' => auth()->id()]);

        $this->post($thread->path()."/subscriptions");

        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test **/
    public function a_user_can_unsubscribe_from_a_thread()
    {
        $this->be(User::factory()->create());

        $thread = Thread::factory()->create(['user_id' => auth()->id()]);

        $this->post($thread->path()."/subscriptions");

        $this->assertEquals(1, $thread->subscriptions->where('user_id', auth()->id())->count());

        $this->delete($thread->path()."/subscriptions");

        $this->assertEquals(0, $thread->subscriptions()->where('user_id', auth()->id())->count());
    }
}
