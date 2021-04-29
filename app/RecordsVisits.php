<?php


namespace App;


use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{
    /**
     * Deletes Recorded Treading thread
     * @return $this
     */
    public function resetVisits(): RecordsVisits
    {
        Redis::del($this->visitCacheKey());

        return $this;
    }

    /**
     * Adds the Recorded Treading Thread
     * @return $this
     */
    public function recordVisits(): RecordsVisits
    {
        Redis::incr($this->visitCacheKey());

        return $this;
    }

    /**
     * Fetches the Id of Treading thread
     *
     * @return int
     */
    public function visits()
    {
        return Redis::get($this->visitCacheKey()) ?? 0;
    }

    /**
     * Sets the Cache key for Trending thread
     * @return string
     */
    public function visitCacheKey(): string
    {
        return "threads.{$this->id}.visits";
    }
}
