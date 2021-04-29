<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
    protected array $filters = ['by', 'popularity', 'unanswered'];

    /**
     * @param $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter by popularity
     *
     * @return mixed
     */
    public function popularity()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('reply_count', 'desc');
    }

    /**
     * Query for reply count
     * @return mixed
     */
    protected function unanswered()
    {
        return $this->builder->where('reply_count', 0);
    }
}
