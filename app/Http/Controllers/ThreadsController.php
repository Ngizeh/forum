<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Rules\Recaptcha;
use App\Thread;
use App\Trending;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;


class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     * Authentication middleware
     * Authorization middleware.
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display list of the threads
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @param Trending $trending
     * @return Application|Factory|View
     */
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


    /**
     * Show a form to create a resource.
     *
     * @return Application|Factory|View
     */
    public function create(): Application|View|Factory
    {
        return view('threads.create');
    }

    /**
     * Store a thread in the database
     *
     * @param Recaptcha $recaptcha
     * @return Application|ResponseFactory|RedirectResponse|Response|Redirector
     */
    public function store(Recaptcha $recaptcha): Application|Response|Redirector|RedirectResponse|ResponseFactory
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

    /**
     * Shows the a specified thread.
     *
     * @param $channel
     * @param Thread $thread
     * @param Trending $trending
     * @return Application|Factory|View
     */
    public function show($channel, Thread $thread, Trending $trending): Application|View|Factory
    {
        if(auth()->check()){
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->recordVisits();

        return view('threads.show', compact('thread'));
    }

    /**
     * Updates the specific thread
     *
     * @param $channel
     * @param Thread $thread
     * @throws AuthorizationException
     * @return void
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

    /**
     * Deletes specified thread from the database
     *
     * @param $channel
     * @param Thread $thread
     * @param Trending $trending
     * @return Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function destroy($channel, Thread $thread, Trending $trending)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        $trending->pop($thread);

        $thread->resetVisits();

        return redirect('/threads');
    }

    /**
     * Filters the threads for a given channel
     * Helper method for fetching trending threads.
     *
     * @param $channel
     * @param $filters
     * @return mixed
     */
    public function getThreads($channel, $filters): mixed
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(5);
    }
}
