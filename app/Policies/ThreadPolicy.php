<?php

namespace App\Policies;

use App\User;
use App\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @return bool
     */
    public function before($user)
    {
        if ($user->name == "Smith Doe") {
            return true;
        }
    }

    /**
     * Authorization for updating ana deleting a resource.
     *
     * @param User $user
     * @param Thread $thread
     * @return bool
     */
    public function update(User $user, Thread $thread)
    {
        //Reason being you can do edit,update and delete
        return auth()->id() == $thread->user_id;
    }

}
