<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArchiveChannelsController extends Controller
{

    public function store(Channel $channel)
    {
        $channel->archive();
    }

    public function destroy(Channel $channel)
    {
        $channel->unarchive();
    }
}
