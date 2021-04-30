<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Illuminate\Contracts\Foundation\Application;

class ProfilesController extends Controller
{
    /**
     * @param User $user
     * @return Application|Factory|View
     */
    public function show(User $user)
    {
        return view('profile.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ]);
    }
}
