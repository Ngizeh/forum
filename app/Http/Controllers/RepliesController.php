<?php

namespace App\Http\Controllers;

use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                use App\Rules\SpamFree;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                use App\Http\Requests\ReplyRequest;
use App\User;
use Illuminate\Http\Request;


class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(25);
    }

    public function store($channel, Thread $thread, ReplyRequest $request)
    {
        return $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ])->load('owner');

    }

    public function update(Reply $reply, Request $request)
    {
        $this->authorize('update', $reply);

        $request->validate(['body' => 'required|spamfree']);

        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

}
