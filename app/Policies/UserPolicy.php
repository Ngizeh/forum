<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Authorization for updating and deleting a resource.
     *
     * @param User $user
     * @param User $signedUser
     * @return bool
     */
    public function update(User $user, User $signedUser): bool
    {
        return $signedUser->id === $user->id;
    }
}
