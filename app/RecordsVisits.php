<?php


namespace App;


use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{
    /**
     * Deletes Recorded Treading thread.
     *
     * @return Thread
     */
    public function resetVisits(): Thread
    {
        Redis::del($this->visitCacheKey());

        return $this;
    }

    /**
     * Adds the Recorded Treading Thread
     *
     * @return Thread
     */
    public function recordVisits(): Thread
    {
        Redis::incr($this->visitCacheKey());

        return $this;
    }

    /**
     * Fetches the Id of Treading thread
     *
     * @return int
     */
    public function visits(): int
    {
        return Redis::get($this->visitCacheKey()) ?? 0;
    }

    /**
     * Sets the Cache key for Trending thread.
     *
     * @return string
     */
    public function visitCacheKey(): string
    {
        return "threads.{$this->id}.visits";
    }
}
