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
    /**
     * Eager Loading the Reply with owner and Favorites
     * @var string[]
     */
	protected $with = ['owner', 'favorites'];

    /**
     * Provides the array pf attributes
     * @var string[]
     */
	protected $appends = ['isFavorited', 'favoritedCount', 'isBest'];

    /**
     * Model Event for Reputation
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
     * @return BelongsTo
     */
	public function owner(): BelongsTo
    {
		return $this->belongsTo(User::class, 'user_id');
	}

    /**
     * @return BelongsTo
     */
	public function thread(): BelongsTo
    {
		return $this->belongsTo(Thread::class);
	}

    /**
     * Created At mutator
     * @return mixed
     */
	public function wasJustPublished()
	{
		return $this->created_at->gt(Carbon::now()->subMinute());
	}

    /**
     * Sanitizes the body attribute
     * @param $body
     */
	public function setBodyAttribute($body)
	{
		$this->attributes['body'] = preg_replace('/@([\w\-]+)/','<a href="/profile/$1">$0</a>', $body );
	}

    /**
     * Gets the mentioned user appending the @ symbol infront
     * @return mixed
     */
	public function mentionedUser()
	{
		preg_match_all('/@([\w\-]+)/', $this->body, $matches);

		return $matches[1];
	}

    /**
     * Marks the best reply.
     * @return bool
     */
	public function isBest(): bool
    {
		return $this->thread->best_reply_id == $this->id;
	}

    /**
     * Appends best attribute mutator
     * @return bool
     */
	public function getIsBestAttribute(): bool
    {
		return $this->isBest();
	}

    /**
     * Makes sure the body is sanitize
     * @param $body
     * @return mixed
     */
	public function getBodyAttribute($body)
	{
		return \Purify::clean($body);
	}

}
