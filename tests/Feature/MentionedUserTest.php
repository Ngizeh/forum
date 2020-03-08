<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionedUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function all_mentioned_in_the_reply_users_get_notified()
    {
       $john = factory(User::class)->create(['name' => 'John']);

       $this->be($john);

       $thread = factory(Thread::class)->create();

       $jane = factory(User::class)->create(['name' => 'Jane']);

       $reply = factory(Reply::class)->make([
           'body' => 'Hi @John see what this says'
       ]);

       $this->json('post', $thread->path().'/replies', $reply->toArray());

       $this->assertCount(1, $john->notifications);

    }

    /** @test **/
    public function it_can_fetch_all_the_users_mentioned_starting_with_the_given_character()
    {
       $janeDoe = factory(User::class)->create(['name' => 'JaneDoe']);
       $johnDoe = factory(User::class)->create(['name' => 'JohnDoe']);
       $janeDoe2 = factory(User::class)->create(['name' => 'JaneDoe2']);

      $response =  $this->json('get', '/api/users', ['name' => 'JaneDoe']);

       $this->assertCount(2, $response->json());
    }
}
