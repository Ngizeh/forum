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
     * Cast the attribute to boolen
     *
     * @var string[]
     */
    protected $casts = [
        'confirmed' => 'boolean'
    ];

    /**
     * Return the route a string slug
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
    public function getIsAdminAttribute(): bool
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

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Mark the Registered users confirmed Email.
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

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("user.%s.visits.%s", $this->id, $thread->id);
    }
}
