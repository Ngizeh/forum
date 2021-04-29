<?php


namespace App;


use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * @return array
     */
    public function get(): array
    {
        return array_map('json_decode' , Redis::ZREVRANGE($this->cacheKey(), 0,  4));
    }

    /**
     * @param $thread
     */
    public function push($thread)
    {
         Redis::ZINCRBY($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    /**
     * @param $thread
     */
    public function pop($thread)
    {
         Redis::ZREM($this->cacheKey(), json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    /**
     * @return string
     */
    public function cacheKey(): string
    {
         return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    /**
     * Deletes Trending Thread
     * @return void
     */
    public function reset()
    {
        Redis::del($this->cacheKey());
    }

}
