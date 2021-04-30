<?php

namespace App;

use App\Favoritable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
	use Favoritable, RecordActivity, HasFactory;

	protected $guarded  = [];

	protected $with = ['owner', 'favorites'];

	protected $appends = ['isFavorited', 'favoritedCount', 'isBest'];

    /**
     * Model Event is register for Created Reply
     */
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

    /**
     * @return string
     */
	public function path(): string
    {
		return $this->thread->path() . "#reply-{$this->id}";
	}

    /**
     * Relationship
     * @return BelongsTo
     */
	public function owner(): BelongsTo
    {
		return $this->belongsTo(User::class, 'user_id');
	}

    /**
     * Relationship
     * @return BelongsTo
     */
	public function thread(): BelongsTo
    {
		return $this->belongsTo(Thread::class);
	}

    /**
     * Access Mutator.
     *
     * @return mixed
     */
	public function wasJustPublished()
	{
		return $this->created_at->gt(Carbon::now()->subMinute());
	}

    /**
     * Access modifier.
     *
     * @param $body
     */
	public function setBodyAttribute($body)
	{
		$this->attributes['body'] = preg_replace('/@([\w\-]+)/','<a href="/profile/$1">$0</a>', $body );
	}

    /**
     * @return mixed
     */
	public function mentionedUser()
	{
		preg_match_all('/@([\w\-]+)/', $this->body, $matches);

		return $matches[1];
	}

    /**
     * @return bool
     */
	public function isBest(): bool
    {
		return $this->thread->best_reply_id == $this->id;
	}

    /**
     * Access mutator
     * @return bool
     */
	public function getIsBestAttribute(): bool
    {
		return $this->isBest();
	}

    /**
     * Access modifier.
     *
     * @param $body
     * @return mixed
     */
	public function getBodyAttribute($body)
	{
		return \Purify::clean($body);
	}

}
