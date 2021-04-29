<?php

namespace App;

/**
 *
 */
class Reputation
{
    const THREAD_CREATED = 10;
    const REPLY_CREATED = 2;
    const REPLY_MARKED_AS_BEST = 50;
    const REPLY_FAVORITED = 5;

    /**
     * @param $user
     * @param $points
     */
    public static function award($user, $points)
    {
       $user->increment('reputation', $points);
    }

    /**
     * @param $user
     * @param $points
     */
    public static function loose($user, $points)
    {
       $user->decrement('reputation', $points);
    }
}

