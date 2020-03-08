<?php

namespace App;

trait RecordActivity
{
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

    protected static function activityEvent()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        return $this->activities()->create([
               'user_id' => auth()->id(),
               'type' => $this->getActivityType($event)
            ]);
    }

    protected function getActivityType($event)
    {
        return $event.'_'.strtolower((new \ReflectionClass($this))->getShortName());
    }


    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
