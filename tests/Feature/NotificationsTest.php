<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;
    protected $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->be(factory(User::class)->create());

        $this->user = auth()->user();

        $this->thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $this->thread->subscribe();

        $this->thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body' => 'Some body'
        ]);
    }

    /** @test **/
    public function a_notification_is_prepared_where_a_subscriptions_button_is_clicked()
    {
        $this->thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some body'
        ]);

        $this->assertCount(1, $this->user->fresh()->notifications);

    }

    /** @test * */
    public function user_can_get_all_unread_notifications()
    {
        $this->withoutExceptionHandling();

        $response = $this->getJson("/profile/".$this->user->name."/notifications/")->json();

        $this->assertCount(1, $response);
    }

    /** @test * */
    public function a_user_can_mark_as_read()
    {
        $this->withoutExceptionHandling();

        $this->assertCount(1, $this->user->unreadNotifications);

        $notificationId = $this->user->unreadNotifications->first()->id;

        $this->delete("/profile/".$this->user->name."/notifications/".$notificationId);

        $this->assertCount(0, $this->user->fresh()->unreadNotifications);
    }
}
