<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function guest_can_not_favorite_a_reply()
    {
        $this->post("replies/1/favorites")
              ->assertRedirect('/login');
    }

    /** @test **/
    public function a_reply_can_be_favorited()
    {
        // $this->withoutExceptionHandling();

        $this->be(factory(User::class)->create());

        $reply = factory(Reply::class)->create();

        $this->post("replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }

    /** @test **/
    public function a_reply_can_be_unfavorited()
    {
        $this->be(factory(User::class)->create());

        $reply = factory(Reply::class)->create();

        $reply->favorite();

        $this->delete("replies/{$reply->id}/favorites");

        $this->assertCount(0, $reply->favorites);
    }


    /** @test **/
    public function a_reply_can_not_be_favorited_twice()
    {
        // $this->withoutExceptionHandling();

        $this->be(factory(User::class)->create());

        $reply = factory(Reply::class)->create();

        try {
            $this->post("replies/{$reply->id}/favorites");
            $this->post("replies/{$reply->id}/favorites");
        } catch (\Exception $e) {
            $this->fail('Can favorite twice, Man!');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
