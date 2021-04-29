<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\Factory;
use Illuminate\View\View;

class ChannelsController extends Controller
{
    /**
     * @param Channel $channel
     * @return Application|Factory|View
     */
	public function index(Channel $channel)
	{
		$channels = Channel::latest('created_at')->paginate(8);

		  if (request()->wantsJson()) {
            return $channels;
        }

		return view('admin.channel.index', compact('channels'));
	}

    /**
     * Displays a view for channels
     *
     * @return Application|Factory|View
     */
	public function create()
	{
		return view('admin.channel.create');
	}

    /**
     * @param Channel $channel
     * @return Application|ResponseFactory|RedirectResponse|Response|Redirector
     */
	public function store(Channel $channel)
	{
		$data = request()->validate([
			'name' => 'required',
			'description' => 'required'
		]);

		$channel->create($data + [ 'slug' => Str::slug($data['name'])]);

		// Cache::forget('channels');

		if (request()->wantsJson()) {
			return response($channel, 201);
		}

		return redirect(route('admin.channels.index'))
		->with('flash', 'Your channel has been created!');
	}

    /**
     * Create s channel resource in database.
     *
     * @param Channel $channel
     * @return Application|ResponseFactory|Response
     */
	public function update(Channel $channel)
	{
		$data = request()->validate([
			'name' => 'required',
			'description' => 'required'
		]);

	    $channel->update($data + [ 'slug' => Str::slug($data['name'])]);

	    if (request()->wantsJson()) {
			return response($channel, 201);
		}

		// Cache::forget('channels');
	}

    /**
     * Removes a specified channel
     *
     * @param $channelId
     * @return Application|ResponseFactory|Response
     */
	public function destroy($channelId)
	{
		Channel::findOrFail($channelId)->delete();

		// Cache::forget('channels');

		if (request()->wantsJson()) {
			return response(201);
		}
	}


}
