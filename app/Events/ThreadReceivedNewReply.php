<?php

namespace App\Events;

use App\Reply;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ThreadReceivedNewReply
{
    use Dispatchable, SerializesModels;

    public Reply $reply;

    /**
     * ThreadReceivedNewReply constructor.
     * @param $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }


}
