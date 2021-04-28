<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateThreadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
       parent::setUp();

       $this->be(User::factory()->create());
    }

    /** @test **/
    public function a_thread_can_be_update_by_it_creator()
    {
        $thread = Thread::factory()->create(['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
           'title' => 'Changed title',
           'body' => 'Changed body',
        ]);

        tap($thread->fresh(), function($thread){
            $this->assertEquals('Changed body', $thread->body);
            $this->assertEquals('Changed title', $thread->title);
        });
    }

    /** @test **/
     public function a_thread_can_not_be_update_by_it_()
    {
        $thread = Thread::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->patch($thread->path(), [
           'title' => 'Changed title',
           'body' => 'Changed body',
        ])->assertStatus(403);
    }


      /** @test **/
    public function title_and_body_is_required_to_update()
    {
        $thread = Thread::factory()->create(['user_id' => auth()->id()]);

        $this->patch($thread->path(), ['title' => null])->assertSessionHasErrors('title');
        $this->patch($thread->path(), ['body' => null])->assertSessionHasErrors('body');
    }
}
