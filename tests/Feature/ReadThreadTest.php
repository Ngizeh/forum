<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        $this->thread = Thread::factory()->create();
    }

    /** @test **/
    public function a_user_can_view_all_threads()
    {
        $response = $this->get(route('threads'));

        $response->assertSee($this->thread->title);
    }

    /** @test **/
    public function a_user_can_view_a_thread()
    {
        $response = $this->get($this->thread->path());

        $response->assertSee($this->thread->title)->assertSee($this->thread->body);
    }

    /** @test **/
    public function a_thread_can_be_filtered_by_channel()
    {
        $channel = Channel::factory()->create();

        $thread = Thread::factory()->create(['channel_id' => $channel->id]);

        $threadWithOutChannel = Thread::factory()->create();

        $this->get("/threads/{$channel->slug}")
                ->assertSee($thread->title)
                ->assertDontSee($threadWithOutChannel->title);
    }

    /** @test **/
    public function a_thread_can_be_filtered_by_username()
    {
        $this->be(User::factory()->create(['name' => 'SmithDoe']));

        $thread = Thread::factory()->create(['user_id' => auth()->id()]);
        $threadBySomeone = Thread::factory()->create();

        $this->get('/threads?by=SmithDoe')
                ->assertSee($thread->title)
                ->assertDontSee($threadBySomeone->title);
    }

    /** @test */
    public function a_thread_can_be_filtered_by_popularity()
    {
        $threadWithTwoReplies = Thread::factory()->create();
        Reply::factory()->times(2)->create(['thread_id' => $threadWithTwoReplies->id]);

        $threadWithThreeReplies = Thread::factory()->create();
        Reply::factory()->times(3)->create(['thread_id' => $threadWithThreeReplies->id]);

        $response = $this->getJson('/threads?popularity=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response['data'], 'reply_count'));
    }

    /** @test **/
    public function user_can_filter_threads_that_are_unanswered()
    {
        $response = $this->getJson('/threads?unanswered=1')->json();

        $this->assertEquals(0, $this->thread->fresh()->reply_count);
    }

    /** @test **/
    public function user_can_get_all_the_threads_of_the_reply()
    {
        $replies = Reply::factory()->times(2)->create(['thread_id' => $this->thread->id]);

        $response  = $this->getJson($this->thread->path()."/replies")->json();

        $this->assertEquals(2, $replies->count());
    }
}
