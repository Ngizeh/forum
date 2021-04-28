<?php

namespace Tests\Unit;

use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{
	use RefreshDatabase;

     /** @test **/
   public function it_has_a_thread_on_a_channel()
   {
   		$this->withoutExceptionHandling();

   		$channel = Channel::factory()->create();
   		$thread = Thread::factory()->create(['channel_id' => $channel->id]);

   		$this->assertTrue($channel->threads->contains($thread));
   }

   /** @test **/
   public function channel_can_be_archived()
   {
        $channel = Channel::factory()->create();

        $channel->archive();

        $this->assertDatabaseHas('channels', ['archive' => true]);
   }

   /** @test **/
   public function channel_can_be_unarchived()
   {
        $channel = Channel::factory()->create();

        $channel->unarchive();

        $this->assertDatabaseHas('channels', ['archive' => false]);
   }



}
