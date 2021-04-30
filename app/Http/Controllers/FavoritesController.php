<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Favorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoritesController extends Controller
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
     * Stores favorite reply resource.
     *
     * @param Reply $reply
     * @return RedirectResponse
     */
    public function store(Reply $reply): RedirectResponse
    {
        $reply->favorite();

        return back()->with('flash', 'You have favorited the reply');
    }

    /**
     * Create a resource for a favorite
     *6
     * @param Reply $reply
     */
    public function destroy(Reply $reply)
    {
        $reply->unFavorite();
    }
}
