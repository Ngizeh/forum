<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function user_can_see_their_last_reply()
    {
        $user = factory(User::class)->create();

        $reply = factory(Reply::class)->create(['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test **/
    public function it_can_determine_user_avatar()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(asset('storage/avatars/default.png'), $user->avatar_path);

        $user->avatar_path = 'storage/avatars/me.jpg';

        $this->assertEquals(asset('storage/avatars/me.jpg'), $user->avatar_path);
    }
}
