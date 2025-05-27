<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            ActivityLog::log('created', $model, [], $model->toArray());
        });

        static::updated(function ($model) {
            ActivityLog::log(
                'updated',
                $model,
                $model->getOriginal(),
                $model->getChanges()
            );
        });

        static::deleted(function ($model) {
            ActivityLog::log('deleted', $model, $model->toArray(), []);
        });

        static::forceDeleted(function ($model) {
            ActivityLog::log('force_deleted', $model, $model->toArray(), []);
        });
    }
}
