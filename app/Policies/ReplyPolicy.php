<?php

namespace App\Policies;

use App\User;
use App\Reply;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Authorization for update and created resource.
     *
     * @param User $user
     * @param Reply $reply
     * @return bool
     */
    public function update(User $user, Reply $reply): bool
    {
        return $user->id == $reply->user_id;
    }

    /**
     * Authorization for user to publish a resource
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        if(! $lastReply = $user->fresh()->lastReply) return true ;

        return ! $lastReply->wasJustPublished();
    }
}
