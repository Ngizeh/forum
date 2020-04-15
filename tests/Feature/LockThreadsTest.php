<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function non_admininstrators_can_not_lock_a_thread()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->post(route('locked-thread.store', $thread), [
            'locked' => true,
        ])->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test **/
    public function admininstrators_can_lock_a_thread()
    {
        $this->withoutExceptionHandling();

        $this->be(factory(User::class)->states('admin')->create());

        $thread = factory(Thread::class)->create();

        $this->post(route('locked-thread.store', $thread), [
            'locked' => true,
        ])->assertStatus(200);

        $this->assertTrue($thread->fresh()->locked);

    }

     /** @test **/
    public function admininstrators_can_unlock_a_thread()
    {
        $this->withoutExceptionHandling();

        $this->be(factory(User::class)->states('admin')->create());

        $thread = factory(Thread::class)->create(['locked' => true]);

        $response = $this->delete(route('locked-thread.destroy', $thread), [
            'locked' => false,
        ]);

        $this->assertFalse($thread->fresh()->locked);

    }

   /** @test **/
   public function once_the_thread_is_locked_it_can_not_be_updated()
   {
        $this->withoutExceptionHandling();

        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['locked' => true]);

        $response = $this->post($thread->path().'/replies', [
            'body' => 'test',
            'user_id' => auth()->id()
        ]);

        $response->assertStatus(422);

   }



}
