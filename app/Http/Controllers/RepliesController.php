<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     * Authorization middleware.
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Displays the list of the Replies.
     *
     * @param $channel
     * @param Thread $thread
     * @return LengthAwarePaginator
     */
    public function index($channel, Thread $thread): LengthAwarePaginator
    {
        return $thread->replies()->paginate(25);
    }

    /**
     * @param $channel
     * @param Thread $thread
     * @param ReplyRequest $request
     * @return Application|ResponseFactory|Model|Response
     */
    public function store($channel, Thread $thread, ReplyRequest $request)
    {
        if($thread->locked){
            return response('Thread is locked', 422);
        }
        return $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ])->load('owner');

    }

    /**
     * Updates a resource.
     *
     * @param Reply $reply
     * @throws AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $data = request()->validate(['body' => 'required|spamfree']);

        $reply->update($data);
    }

    /**
     * @param Reply $reply
     * @return Application|ResponseFactory|RedirectResponse|Response
     * @throws AuthorizationException
     */
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
