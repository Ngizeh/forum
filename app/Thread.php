<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Reputation;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordActivity, RecordsVisits;

    protected $guarded = [];

    protected $with = ['channel', 'creator'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = ['locked' => 'boolean'];

    public function getRouteKeyName()
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

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->withCount('favorites');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function hasUpdatesFor($user)
    {
        $user = auth()->user();

        $key = $user->visitedThreadCacheKey($this);

        return  $this->updated_at > cache($key);

    }

    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);

        if(static::whereSlug($slug)->exists()){
            $slug = "{$slug}-".$this->id;
        }
        $this->attributes['slug'] = $slug;
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
        Reputation::award($reply->owner, Reputation::REPLY_MARKED_AS_BEST);
    }

    public function getBodyAttribute($body)
    {
       return \Purify::clean($body);
    }

}
