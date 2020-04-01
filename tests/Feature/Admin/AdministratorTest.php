<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdministratorTest extends TestCase
{
    use RefreshDatabase;


    /** @test **/
    public function admin_can_access_admin_section()
    {
        $admin = factory(User::class)->states('admin')->create();

        $this->actingAs($admin)
              ->get(route('admin.dashboard.index'))
              ->assertStatus(200);
    }

    /** @test **/
    public function non_admin_cannot_access_admin_section()
    {
        $admin = factory(User::class)->create();

        $this->actingAs($admin)
              ->get(route('admin.dashboard.index'))
              ->assertStatus(403);
    }

}
