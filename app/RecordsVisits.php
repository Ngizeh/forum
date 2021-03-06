<?php


namespace App;


use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{

    public function resetVisits()
    {
        Redis::del($this->visitCacheKey());

        return $this;
    }

    public function recordVisits()
    {
        Redis::incr($this->visitCacheKey());

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitCacheKey()) ?? 0;
    }

    public function visitCacheKey()
    {
        return "threads.{$this->id}.visits";
    }
}
