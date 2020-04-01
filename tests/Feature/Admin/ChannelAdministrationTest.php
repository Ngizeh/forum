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

    protected function setUp()
    {
       parent::setUp();

       $this->withExceptionHandling();
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
        $regularUser = factory(User::class)->create();

        $this->actingAs($regularUser)->get(route('admin.channels.index'))->assertStatus(403);

        $this->actingAs($regularUser)->get(route('admin.channels.create'))->assertStatus(403);
    }

    /** @test **/
    public function admin_can_create_a_channel()
    {
        $this->signInAdmin();

        $channel = factory(Channel::class)->create();

        $response = $this->post(route('admin.channels.store'), $channel->toArray());

        $this->get($response->headers->get('Location'))->assertSee($channel->title)->assertSee($channel->body);
    }

    /** @test **/
    public function admin_can_delete_a_channel()
    {
        $this->withoutExceptionHandling();

        $this->signInAdmin();

        $channel = factory(Channel::class)->create(['name' => 'Javascript', 'slug' => 'javascript']);

        $this->delete(route('admin.channels.destroy', $channel->id));

        $this->assertDatabaseMissing('channels', ['slug' => $channel->slug]);
        $this->assertDatabaseMissing('channels', ['name' => $channel->name]);
    }

    /** @test **/
    public function admin_can_update_a_channel()
    {
        $this->withoutExceptionHandling();

        $this->signInAdmin();

        $channel = factory(Channel::class)->create(['name' => 'PHP', 'description' => 'About PHP']);

        $this->patch("/admin/channels/{$channel->id}", $channel->toArray());

       $this->assertDatabaseHas('channels', [
            'name' => $channel->fresh()->name,
            'description' => $channel->fresh()->description
       ]);
    }

     /** @test **/
    public function name_is_required_to_create_channel()
    {
        $this->signInAdmin();

        $channel = factory(Channel::class)->create(['name' => '']);

        $response = $this->post(route('admin.channels.store'), $channel->toArray());

        $response->assertSessionHasErrors('name');
    }

    /** @test **/
    public function description_is_required_to_create_channel()
    {
        $this->signInAdmin();

        $channel = factory(Channel::class)->create(['description' => '']);

        $response = $this->post(route('admin.channels.store'), $channel->toArray());

        $response->assertSessionHasErrors('description');
    }
}
