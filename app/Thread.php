<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Reputation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Thread extends Model
{
	use RecordActivity, RecordsVisits, HasFactory;

	protected $guarded = [];

	protected $with = ['channel', 'creator'];

	protected $appends = ['isSubscribedTo'];

	protected $casts = ['locked' => 'boolean'];

    /**
     * Returns the route as a string.
     *
     * @return string
     */
	public function getRouteKeyName(): string
    {
		return 'slug';
	}

	protected static function boot()
	{
		parent::boot();

		static::deleting(function ($thread) {
			$thread->replies->each->delete();
			Reputation::loose($thread->creator, Reputation::THREAD_CREATED);
		});

		static::created(function ($thread) {
			$thread->update(['slug' => $thread->title]);
			Reputation::award($thread->creator, Reputation::THREAD_CREATED);
		});
	}

    /**
     * Model route path
     * @return string
     */
	public function path(): string
    {
		return "/threads/{$this->channel->slug}/{$this->slug}";
	}

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
	public function scopeFilter($query, $filters)
	{
		return $filters->apply($query);
	}

    /**
     * @return BelongsTo
     */
	public function channel(): BelongsTo
    {
		return $this->belongsTo(Channel::class);
	}

    /**
     * @return BelongsTo
     */
	public function creator(): BelongsTo
    {
		return $this->belongsTo(User::class, 'user_id');
	}

    /**
     * @return HasMany
     */
	public function replies(): HasMany
    {
		return $this->hasMany(Reply::class)->withCount('favorites');
	}

    /**
     * @param $reply
     * @return Model
     */
	public function addReply($reply): Model
    {
		$reply = $this->replies()->create($reply);

		event(new ThreadReceivedNewReply($reply));

		return $reply;
	}

    /**
     * @param null $userId
     * @return $this
     */
	public function subscribe($userId = null): Thread
    {
		$this->subscriptions()->create([
			'user_id' => $userId ?: auth()->id()
		]);

		return $this;
	}

    /**
     * @param null $userId
     */
	public function unsubscribe($userId = null)
	{
		$this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
	}

    /**
     * Access modifier.
     *
     * @return bool
     */
	public function getIsSubscribedToAttribute()
	{
		return $this->subscriptions()->where('user_id', auth()->id())->exists();
	}

    /**
     * @return HasMany
     */
	public function subscriptions(): HasMany
    {
		return $this->hasMany(ThreadSubscription::class);
	}

    /**
     * @param $user
     * @return bool
     * @throws \Exception
     */
	public function hasUpdatesFor($user): bool
    {
		$user = auth()->user();

		$key = $user->visitedThreadCacheKey($this);

		return  $this->updated_at > cache($key);

	}

    /**
     * Access mutator.
     *
     * @param $value
     */
	public function setSlugAttribute($value)
	{
		$slug = Str::slug($value);

		if(static::whereSlug($slug)->exists()){
			$slug = "{$slug}-".$this->id;
		}
		$this->attributes['slug'] = $slug;
	}

    /**
     * Marks the best reply and updates the Reputation counter.
     *
     * @param Reply $reply
     */
	public function markBestReply(Reply $reply)
	{
		$this->update(['best_reply_id' => $reply->id]);
		Reputation::award($reply->owner, Reputation::REPLY_MARKED_AS_BEST);
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

    /**
     * Access modifier.
     *
     * @return string
     */
	public function pluralized(): string
    {
		return Str::plural('reply', $this->reply_count);
	}

    /**
     * Access modifier.
     *
     * @return string
     */
	public function excerpt(): string
    {
		return Str::limit($this->title, 20);
	}


}
