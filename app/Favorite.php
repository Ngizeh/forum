<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    use RecordActivity;

    protected $guarded = [];

    /**
     * @return MorphTo
     */
    public function favoritable(): MorphTo
    {
        return $this->morphTo();
    }
}
