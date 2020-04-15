<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $guarded = [];

    protected $appends = ['ThreadsCount'];

    protected $casts = ['archive' => 'boolean'];

    public function scopeWithArchive($query)
    {
       return $query->where('archive', false)->get();
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

    public function archive()
    {
        $this->update(['archive' => true]);
    }

    public function unarchive()
    {
        $this->update(['archive' => false]);
    }



}
