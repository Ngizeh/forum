<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Factory;

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
