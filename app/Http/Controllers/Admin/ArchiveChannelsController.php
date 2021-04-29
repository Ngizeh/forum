<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArchiveChannelsController extends Controller
{
    /**
     * Store an Channel as archived
     *
     * @param Channel $channel
     */
    public function store(Channel $channel)
    {
        $channel->archive();
    }

    /**
     * Removes the channel from the archive
     * @param Channel $channel
     */
    public function destroy(Channel $channel)
    {
        $channel->unarchive();
    }
}
