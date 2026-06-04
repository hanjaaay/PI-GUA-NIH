<?php

namespace App\Traits;

use App\Models\Activity;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function logActivity($type)
    {
        if (! auth()->check()) {
            return;
        }

        $description = $this->getActivityDescription($type);

        $this->activities()->create([
            'user_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
            'properties' => $this->getActivityProperties(),
        ]);
    }

    protected function getActivityDescription($type)
    {
        $modelName = class_basename($this);

        return match ($type) {
            'created' => "Created new {$modelName}",
            'updated' => "Updated {$modelName}",
            'deleted' => "Deleted {$modelName}",
            default => "Performed action on {$modelName}"
        };
    }

    protected function getActivityProperties()
    {
        return [
            'old' => $this->getOriginal(),
            'attributes' => $this->getAttributes(),
        ];
    }
}
