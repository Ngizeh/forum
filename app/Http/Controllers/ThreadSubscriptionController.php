<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionController extends Controller
{
    public function store($channel, Thread $thread)
    {
        return $thread->subscribe(auth()->id());
    }

    public function destroy($channel, Thread $thread)
    {
        return $thread->unsubscribe(auth()->id());
    }
}
