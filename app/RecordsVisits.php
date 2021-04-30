<?php


namespace App;


use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{

    /**
     * @return Thread
     */
    public function resetVisits(): Thread
    {
        Redis::del($this->visitCacheKey());

        return $this;
    }

    /**
     * @return Thread
     */
    public function recordVisits(): Thread
    {
        Redis::incr($this->visitCacheKey());

        return $this;
    }

    /**
     * @return int
     */
    public function visits(): int
    {
        return Redis::get($this->visitCacheKey()) ?? 0;
    }

    /**
     * @return string
     */
    public function visitCacheKey(): string
    {
        return "threads.{$this->id}.visits";
    }
}
