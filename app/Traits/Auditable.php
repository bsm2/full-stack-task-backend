<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('create', $model->getAttributes());
        });

        static::updated(function ($model) {
            $original = $model->getOriginal();
            $after = $model->getAttributes();
            $model->logAudit('update', [
                'before' => $original,
                'after' => $after,
            ]);
        });

        static::deleted(function ($model) {
            $model->logAudit('delete', $model->getAttributes());
        });
    }

    /**
     * Helper to create an AuditLog entry.
     *
     * @param string $action
     * @param mixed $changes
     * @return \App\Models\AuditLog
     */
    public function logAudit(string $action, $changes = null): AuditLog
    {
        $userId = Auth::id() ?? User::first()?->id;
        return AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => static::class,
            'entity_id' => $this->getKey(),
            'changes' => $changes,
        ]);
    }
}
