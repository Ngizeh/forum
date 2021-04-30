<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    /**
     * FavoritesController constructor.
     * Authorization middleware
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return auth()->user()->unreadNotifications;
    }

    /**
     * @param User $user
     * @param $notification
     */
    public function destroy(User $user, $notification)
    {
        auth()->user()->notifications()->findOrFail($notification)->markAsRead();
    }
}
