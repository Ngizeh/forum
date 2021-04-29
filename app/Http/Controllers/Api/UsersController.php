<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * List the user by search name.
     *
     * @return mixed
     */
    public function index()
    {
        $search = request('name');

        return User::where('name', "LIKE", "$search%")->take(5)->pluck('name');
    }

    public function store()
    {
         request()->validate([
            'avatar' => 'required|image'
        ]);

        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars','public')
        ]);

        return response()->json([], 204);
    }
}
