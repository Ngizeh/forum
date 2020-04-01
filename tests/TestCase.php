<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        DB::statement('PRAGMA foreign_keys=on');
    }

      protected function signInAdmin($admin = null)
    {
        $admin = $admin ?: factory(User::class)->states('admin')->create();

        // config(['council.administrators' => [$admin->email]]);

        $this->actingAs($admin);

        return $this;
    }
}
