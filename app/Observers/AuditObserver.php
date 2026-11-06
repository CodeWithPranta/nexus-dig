<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->log($model, 'created');
    }

    public function updated(Model $model): void
    {
        $this->log($model, 'updated', $model->getChanges());
    }

    public function deleted(Model $model): void
    {
        $this->log($model, 'deleted');
    }

    /**
     * @param  array<string, mixed>  $changes
     */
    private function log(Model $model, string $action, array $changes = []): void
    {
        AuditLog::query()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $model::class,
            'subject_id' => $model->getKey(),
            'changes' => $changes === [] ? null : $changes,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 1000),
        ]);
    }
}
