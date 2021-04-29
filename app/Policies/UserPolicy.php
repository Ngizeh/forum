<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Authorizes is the signed user can not editor a resource that does not belong to them
     * @param User $user
     * @param User $signedUser
     * @return bool
     */
    public function update(User $user, User $signedUser): bool
    {
        return $signedUser->id === $user->id;
    }
}
