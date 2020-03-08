<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popularity', 'unanswered'];


    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    public function popularity()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('reply_count', 'desc');
    }

    protected function unanswered()
    {
        return $this->builder->where('reply_count', 0);
    }
}
