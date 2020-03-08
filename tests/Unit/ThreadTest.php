<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test **/
    public function it_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }


    /** @test **/
    public function it_has_as_reply()
    {
        $replies = factory(Reply::class, 4)->create(['thread_id' => $this->thread->id]);

        //Use collection instance if the relation is of hasMany or BelongsToMany
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function it_can_add_the_reply()
    {
        $reply = factory(Reply::class)->raw();

        $this->thread->addReply($reply);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test * */
    public function notifies_all_the_subscribed_users_that_reply_has_be_created()
    {
        Notification::fake();

        $this->be(factory(User::class)->create());

        $this->thread->subscribe()->addReply([
            'user_id' => 1,
            'body' => 'Some Reply Here'
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);

    }

    /** @test **/
    public function it_belongs_to_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test **/
    public function it_has_a_path()
    {
        $this->assertEquals('/threads/'.$this->thread->channel->slug.'/'.$this->thread->slug, $this->thread->path());
    }

    /** @test **/
    public function thread_can_be_subscribed_to()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $thread->subscribe();

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', auth()->id())->count()
        );
    }

    /** @test **/
    public function thread_can_be_unsubscribed_from()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $thread->unsubscribe();

        $this->assertEquals(
            0,
            $thread->subscriptions()->where('user_id', auth()->id())->count()
        );
    }

    /** @test **/
    public function check_to_see_if_a_thread_is_subscribed_to()
    {
        $this->be(factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test * */
    public function checks_for_the_unread_updates_on_the_thread_and_notify_the_user()
    {
        $this->be(factory(User::class)->create());

        $this->assertTrue($this->thread->hasUpdatesFor(auth()->user()));

        auth()->user()->read($this->thread);

        $this->assertFalse($this->thread->hasUpdatesFor(auth()->id()));

    }

    /** @test **/
    public function a_thread_records_each_thread()
    {

        $thread = factory(Thread::class)->make(['id' => 1]);

        $thread->resetVisits();

        $thread->recordVisits();

        $this->assertEquals(1, $thread->visits());

        $thread->recordVisits();

        $this->assertEquals(2, $thread->visits());
    }
}
