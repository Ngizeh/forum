<?php

use App\Reply;
use App\Thread;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	Thread::factory()->times(50)->create()
    	->each(fn($thread) => Reply::factory()->times(10)->create(['thread_id' => $thread->id]));
    }
}
