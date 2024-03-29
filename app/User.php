<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\Boolean;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_path', 'confirmed', 'confirmation_token'
    ];

    /**
     * Append the attribute to model
     *
     * @var string[]
     */
    protected $appends = [ 'isAdmin' ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    /**
     * Casts the keys in the database to boolean
     *
     * @var string[]
     */
    protected $casts = [
        'confirmed' => 'boolean'
    ];

    /**
     * Return the route a string slug.
     * Model route path.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'name';
    }

    /**
     * @return HasMany
     */
    public function thread(): HasMany
    {
        return $this->hasMany(Thread::class)->latest();
    }

    /**
     * Return the Admin of the App
     * Gets the admin names.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array($this->name, ['JohnDoe', 'Maggie Githinji']);
    }

    /**
     * Gets a mutator if user is admin or not
     *
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

    /**
     * Set the avatar's path mutator
     *
     * @param $avatar
     * @return string
     */
    public function getAvatarPathAttribute($avatar): string
    {
        return asset($avatar ?: '/avatars/default.png');
    }

    /**
     * @return HasOne
     */
    public function lastReply(): HasOne
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     * @return HasMany
     */
    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Mark the Registered users confirmed Email.
     * Updates the confirm email and the token
     *
     * @return void
     */
    public function confirm()
    {
        $this->confirmed = true;

        $this->confirmation_token = null;

        $this->save();
    }

    /**
     * Reads the cached threads keys.
     *
     * @param $thread
     * @return bool
     * @throws \Exception
     */
    public function read($thread): bool
    {
        return cache()->forever($this->visitedThreadCacheKey($thread), Carbon::now());
    }

    /**
     * @param $thread
     * @return string
     */
    public function visitedThreadCacheKey($thread): string
    {
        return sprintf("user.%s.visits.%s", $this->id, $thread->id);
    }
}
