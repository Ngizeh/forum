<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $guarded = [];

    protected $casts = [
     'archive' => 'boolean'
    ];

    protected $appends = ['ThreadsCount'];

    protected static function boot()
    {
        parent::boot();

       static::addGlobalScope('active', function ($builder) {
           $builder->where('archive', false);
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

    public function archive()
    {
        $this->update(['archive' => true]);
    }

    public function unarchive()
    {
        $this->update(['archive' => false]);
    }

}
