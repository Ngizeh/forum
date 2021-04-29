<?php

namespace App;

use App\RecordActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Gate;

class Activity extends Model
{
	protected $guarded = [];

    /**
     * @return MorphTo
     */
	public function subject(): MorphTo
    {
		return $this->morphTo();
	}

	public static function feed($user)
	{
		return static::where('user_id', $user->id)
			->latest()
			->with('subject')
			->take(50)
			->get()
			->groupBy(function ($activity) {
				return $activity->created_at->format('Y-m-d');
		});
	}


}
