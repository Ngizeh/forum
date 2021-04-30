<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionController extends Controller
{
    /**
     * Create a resource for Thread subscription
     *
     * @param $channel
     * @param Thread $thread
     * @return Thread
     */
    public function store($channel, Thread $thread): Thread
    {
        return $thread->subscribe(auth()->id());
    }

    /**
     * Removes the subscribed Thread
     *
     * @param $channel
     * @param Thread $thread
     */
    public function destroy($channel, Thread $thread) : void
    {
        $thread->unsubscribe(auth()->id());
    }
}
