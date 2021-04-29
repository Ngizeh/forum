<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait RecordActivity
{
    /**
     * Model Event
     */
    protected static function bootRecordActivity()
    {
        if (auth()->guest()) {
            return ;
        }
        foreach (static::activityEvent() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activities()->delete();
        });
    }

    /**
     * @return string[]
     */
    protected static function activityEvent(): array
    {
        return ['created'];
    }

    /**
     * @param $event
     * @return Model
     */
    protected function recordActivity($event): Model
    {
        return $this->activities()->create([
               'user_id' => auth()->id(),
               'type' => $this->getActivityType($event)
            ]);
    }

    /**
     * @param $event
     * @return string
     */
    protected function getActivityType($event): string
    {
        return $event.'_'.strtolower((new \ReflectionClass($this))->getShortName());
    }

    /**
     * @return MorphMany
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
