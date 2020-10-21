<?php

namespace App;

use App\Favoritable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	use Favoritable, RecordActivity;

	protected $guarded  = [];

	protected $with = ['owner', 'favorites'];

	protected $appends = ['isFavorited', 'favoritedCount', 'isBest'];

	protected static function boot()
	{
		parent::boot();

		static::created(function ($reply) {
			$reply->thread->increment('reply_count');
			Reputation::award($reply->owner, Reputation::REPLY_CREATED);
		});

		static::deleted(function ($reply) {
			$reply->thread->decrement('reply_count');
			Reputation::loose($reply->owner, Reputation::REPLY_CREATED);
		});
	}

	public function path()
	{
		return $this->thread->path() . "#reply-{$this->id}";
	}

	public function owner()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function thread()
	{
		return $this->belongsTo(Thread::class);
	}

	public function wasJustPublished()
	{
		return $this->created_at->gt(Carbon::now()->subMinute());
	}

	public function setBodyAttribute($body)
	{
		$this->attributes['body'] = preg_replace('/@([\w\-]+)/','<a href="/profile/$1">$0</a>', $body );
	}


	public function mentionedUser()
	{
		preg_match_all('/@([\w\-]+)/', $this->body, $matches);

		return $matches[1];
	}

	public function isBest()
	{
		return $this->thread->best_reply_id == $this->id;
	}

	public function getIsBestAttribute()
	{
		return $this->isBest();
	}

	public function getBodyAttribute($body)
	{
		return \Purify::clean($body);
	}

}
