<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Favoritable
{
    /**
     * Model Event
     * @return void
     */
    public static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    /**
     * @return MorphMany
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * Model Attribute mutator
     * Access Mutator for favorites count.
     *
     * @return int
     */
    public function getFavoritedCountAttribute(): int
    {
        return $this->favorites()->count();
    }

    /**
     * @return Model
     */
    public function favorite(): Model
    {
        $attributes = ['user_id' => auth()->id()];

        if (! $this->favorites()->where($attributes)->exists()) {
            Reputation::award(auth()->user(), Reputation::REPLY_FAVORITED);
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * Marks resource as favorite
     * Mark a model to un favorite
     */
    public function unFavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()->where($attributes)->get()->each->delete();

        Reputation::loose(auth()->user(), Reputation::REPLY_FAVORITED);
    }

    /**
     * Marks resource as favorite
     *
     * @return bool
     */
    public function isFavorited(): bool
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    /*
     * Access mutator for a favorite attribute
     * @return bool
     */
    public function getIsFavoritedAttribute(): bool
    {
        return $this->isFavorited();
    }
}
