<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log(
        string $event,
        string $auditableType,
        int    $auditableId,
        array  $oldValues = [],
        array  $newValues = []
    ): AuditLog {
        return AuditLog::create([
            'user_id'        => Auth::id(),
            'event'          => $event,
            'auditable_type' => $auditableType,
            'auditable_id'   => $auditableId,
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }

    public function getPaginated(int $perPage = 20, ?string $search = null)
    {
        return AuditLog::with('user')
            ->when($search, fn ($q) => $q->where('event', 'like', "%{$search}%")
                ->orWhere('auditable_type', 'like', "%{$search}%"))
            ->latest()
            ->paginate($perPage);
    }
}
