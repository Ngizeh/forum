<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class BestReplyController extends Controller
{
    /**
     * Create a best reply resource.
     *
     * @param Reply $reply
     * @throws AuthorizationException
     */
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->markBestReply($reply);
    }
}
