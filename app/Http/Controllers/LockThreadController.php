<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockThreadController extends Controller
{
    /**
     * Create a resource.
     *
     * @param Thread $thread
     * @return void
     */
    public function store(Thread $thread)
    {
        $thread->update(['locked' => true]);
    }

    /**
     * Marks a resource as false.
     *
     * @param Thread $thread
     */
    public function destroy(Thread $thread)
    {
        $thread->update(['locked' => false]);
    }
}
