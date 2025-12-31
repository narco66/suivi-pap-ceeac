<?php

namespace App\Traits;

use App\Services\AuditService;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    /**
     * Boot the trait
     */
    protected static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            app(AuditService::class)->log('created', $model);
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);
            
            app(AuditService::class)->log('updated', $model, [
                'changes' => $changes,
                'original' => $model->getOriginal(),
            ]);
        });

        static::deleted(function (Model $model) {
            app(AuditService::class)->log('deleted', $model);
        });
    }
}


