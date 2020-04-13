<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArchiveChannelsController extends Controller
{
    public function update($channelId)
    {
        Channel::findOrFail($channelId)->archive();
    }

    public function destroy($channelId)
    {
       Channel::findOrFail($channelId)->unarchive();
    }
}
