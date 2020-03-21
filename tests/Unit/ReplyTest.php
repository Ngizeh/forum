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
    $reply = factory(Reply::class)->create();

        //Map the one to one relationship i.e belongTo for single instance
    $this->assertInstanceOf(User::class, $reply->owner);
}

/** @test **/
public function body_is_required_for_the_reply()
{
    $this->be(factory(User::class)->create());

    $thread = factory(Thread::class)->create();

    $reply = factory(Reply::class)->make(['body' => null]);

    $response = $this->post($thread->path().'/replies', $reply->toArray());

    $response->assertSessionHasErrors('body');
}

    /** @test **/
    public function it_know_when_it_was_replied()
    {
     $reply = factory(Reply::class)->create();

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
       $reply = factory(Reply::class)->create();

       $this->assertFalse($reply->isBest());

       $reply->thread->update(['best_reply_id' => $reply->id]);

       $this->assertTrue($reply->fresh()->isBest());
    }
}
