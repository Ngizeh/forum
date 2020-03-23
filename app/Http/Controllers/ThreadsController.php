<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Rules\Recaptcha;
use App\Thread;
use App\Trending;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }


    public function create()
    {
        return view('threads.create');
    }

    public function store(Recaptcha $recaptcha)
    {
        request()->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => [$recaptcha]
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
        ]);

        if(request()->wantsJson()){
            return response($thread, 201);
        }

        return redirect($thread->path())->with('flash', 'Your thread has be published');
    }


    public function show($channel, Thread $thread, Trending $trending)
    {
        if(auth()->check()){
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->recordVisits();

        return view('threads.show', compact('thread'));
    }


    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */

    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $data = request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $thread->update($data);
    }

    public function destroy($channel, Thread $thread, Trending $trending)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        $trending->pop($thread);

        $thread->resetVisits();

        return redirect('/threads');
    }

    public function getThreads($channel, $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(5);
    }
}
