<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use App\Thread;
use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function records_an_activity_when_a_thread_is_created()
    {
        $this->be(User::factory()->create());

        $thread = Thread::factory()->create();

        $this->assertDatabaseHas('activities', [
            'subject_id' => $thread->id,
            'subject_type' => "App\Thread",
            'user_id' => auth()->id(),
            'type' => 'created_thread'
        ]);
    }

    /** @test **/
    public function records_an_activity_when_a_reply_is_created()
    {
        $this->be(User::factory()->create());

        $reply = Reply::factory()->create();

        $this->assertDatabaseHas('activities', [
            'subject_id' => $reply->id,
            'subject_type' => "App\Reply",
            'user_id' => auth()->id(),
            'type' => 'created_reply'
        ]);

        $this->assertEquals(2, Activity::count());

        $this->assertEquals(1, $reply->activities()->count());
    }
}
