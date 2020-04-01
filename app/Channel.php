<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $guarded = [];

    protected $appends = ['ThreadsCount'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($channel) {
            $channel->threads()->delete();
        });
    }

    public function getRouteKeyName()
    {
    	return 'slug';
    }
    public function threads()
    {
    	return $this->hasMany(Thread::class);
    }

    public function getThreadsCountAttribute()
    {
       return $this->threads()->count();
    }
}
