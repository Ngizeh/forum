<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ChannelsController extends Controller
{
	public function index(Channel $channel)
	{
		$channels = Channel::latest('created_at')->paginate(8);

		  if (request()->wantsJson()) {
            return $channels;
        }

		return view('admin.channel.index', compact('channels'));
	}

	public function create()
	{
		return view('admin.channel.create');
	}

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

	public function destroy($channelId)
	{
		Channel::findOrFail($channelId)->delete();

		// Cache::forget('channels');

		if (request()->wantsJson()) {
			return response(201);
		}
	}


}
