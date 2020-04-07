<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArchiveChannelsController extends Controller
{
    public function update($channel)
    {
        Channel::findOrFail($channel)->archive();
    }

    public function destroy($channel)
    {
        Channel::findOrFail($channel)->unarchive();
    }
}
