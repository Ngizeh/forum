<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use App\Thread;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
  use RefreshDatabase;

  /** @test **/
  public function reply_belongs_to_an_owner()
  {
    $reply = Reply::factory()->create();

        //Map the one to one relationship i.e belongTo for single instance
    $this->assertInstanceOf(User::class, $reply->owner);
}

/** @test **/
public function body_is_required_for_the_reply()
{
    $this->be(User::factory()->create());

    $thread = Thread::factory()->create();

    $reply = Reply::factory()->make(['body' => null]);

    $response = $this->post($thread->path().'/replies', $reply->toArray());

    $response->assertSessionHasErrors('body');
}

    /** @test **/
    public function it_know_when_it_was_replied()
    {
     $reply = Reply::factory()->create();

     $this->assertTrue($reply->wasJustPublished());

     $reply->created_at = Carbon::now()->subMonth();

     $this->assertFalse($reply->wasJustPublished());
    }

    /** @test **/
    public function it_detects_the_mentioned_users()
    {
     $reply = new Reply([
       'body' => '@JohnDoe wants to talk to @JaneDoe'
    ]);

     $this->assertEquals(['JohnDoe', 'JaneDoe'], $reply->mentionedUser());
    }

    /** @test **/
    public function marches_the_user_to_a_link()
    {
      $reply = new Reply([
        'body' => 'Hello @JaneDoe.'
    ]);

      $this->assertEquals(
        'Hello <a href="/profile/JaneDoe">@JaneDoe</a>.',
        $reply->body);
    }

    /** @test **/
    public function it_know_that_is_best_reply()
    {
       $reply = Reply::factory()->create();

       $this->assertFalse($reply->isBest());

       $reply->thread->update(['best_reply_id' => $reply->id]);

       $this->assertTrue($reply->fresh()->isBest());
    }

      /** @test **/
    public function body_attribute_of_reply_is_sanitized_automatically()
    {
        $reply = Reply::factory()->make(['body' => '<script alert("bad")></script><p>This is good</p']);

        $this->assertEquals('<p>This is good</p>', $reply->body);
    }
}
