<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Channel extends Model
{
	use HasFactory;

    protected $guarded = [];

    protected $appends = ['ThreadsCount'];

    protected $casts = ['archive' => 'boolean'];

    public function scopeWithArchive($query)
    {
       return $query->where('archive', false)->get();
    }

    /**
     * Return the route as a string.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
    	return 'slug';
    }

    /**
     * @return HasMany
     */
    public function threads(): HasMany
    {
    	return $this->hasMany(Thread::class);
    }

    /**
     * @return int
     */
    public function getThreadsCountAttribute(): int
    {
       return $this->threads()->count();
    }

    /**
     * Updates the channel to be archived
     * @return  void
     */
    public function archive()
    {
        $this->update(['archive' => true]);
    }

    /**
     * Updates the channel to be un archived
     * @return  void
     */
    public function unarchive()
    {
        $this->update(['archive' => false]);
    }



}
