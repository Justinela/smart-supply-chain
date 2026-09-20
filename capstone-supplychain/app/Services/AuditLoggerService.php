<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLoggerService
{
    public static function log(string $action, string $module, ?array $oldValues = null, ?array $newValues = null, ?int $userId = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'module' => $module,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'old_values_json' => $oldValues,
            'new_values_json' => $newValues,
            'created_at' => now(),
        ]);
    }
}
