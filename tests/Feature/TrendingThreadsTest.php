<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }

   /** @test **/
   public function it_can_increments_a_threads_score_each_time_it_is_read()
   {
       $this->assertEmpty($this->trending->get());

       $thread = factory(Thread::class)->create();

       $this->call('get', $thread->path());

       $this->assertCount(1, $trending  = $this->trending->get());

       $this->assertEquals($thread->title, $trending[0]->title);

       $this->assertEquals($thread->path(), $trending[0]->path);
   }
}
