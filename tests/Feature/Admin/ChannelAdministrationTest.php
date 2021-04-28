<?php

namespace Tests\Feature\Admin;

use App\Channel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChannelAdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
       parent::setUp();

       // $this->withExceptionHandling();
    }

    /** @test **/
    public function admin_can_view_and_create_channel_section()
    {
        $this->signInAdmin();

        $this->get(route('admin.channels.index'))->assertStatus(200);

        $this->get(route('admin.channels.create'))->assertStatus(200);
    }

     /** @test **/
    public function non_admin_cannot_view_and_create_channel_section()
    {
        $regularUser = User::factory()->create();

        $this->actingAs($regularUser)->get(route('admin.channels.index'))->assertStatus(403);

        $this->actingAs($regularUser)->get(route('admin.channels.create'))->assertStatus(403);
    }

    /** @test **/
    public function admin_can_create_a_channel()
    {

        $this->signInAdmin();

        $channel = Channel::factory()->raw();

        $this->post(route('admin.channels.store'), $channel);

        $this->get(route('admin.channels.index'))->assertSee($channel['name'])->assertSee($channel['description']);
    }

    /** @test **/
    public function admin_can_mark_a_channel_archived()
    {
        $this->withoutExceptionHandling();

        $this->signInAdmin();

        $channel = Channel::factory()->create();

        $this->post(route('admin-archive.store', $channel));

        $this->assertDatabaseHas('channels', ['archive' => true]);
    }

     /** @test **/
    public function can_mark_a_channel_unarchived()
    {
        $this->withoutExceptionHandling();

       $this->signInAdmin();

        $channel = Channel::factory()->create(['archive' => true]);

        $this->delete(route('archive-channel.destroy', $channel));

        $this->assertFalse($channel->fresh()->archive);
    }

    /** @test **/
    public function admin_can_update_a_channel()
    {
        $this->withoutExceptionHandling();

        $this->signInAdmin();

        $channel = Channel::factory()->create();

        $this->patch("/admin/channels/{$channel->slug}", [
          'name' => 'PHP',
          'description' => 'About PHP'
        ]);

       $this->assertDatabaseHas('channels', [
            'name' => 'PHP',
            'description' => 'About PHP'
       ]);
    }

     /** @test **/
    public function name_is_required_to_create_channel()
    {
        $this->signInAdmin();

        $channel = Channel::factory()->create(['name' => '']);

        $response = $this->post(route('admin.channels.store'), $channel->toArray());

        $response->assertSessionHasErrors('name');
    }

    /** @test **/
    public function description_is_required_to_create_channel()
    {
        $this->signInAdmin();

        $channel = Channel::factory()->create(['description' => '']);

        $response = $this->post(route('admin.channels.store'), $channel->toArray());

        $response->assertSessionHasErrors('description');
    }
}
