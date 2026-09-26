<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to quickly log an admin action.
     */
    public static function log($arg1, $arg2 = null, $arg3 = null, $arg4 = null, $arg5 = null)
    {
        try {
            $userId = null;
            $action = null;
            $entityType = null;
            $entityId = null;
            $description = null;

            if (is_numeric($arg1)) {
                // (userId, action, entityType, entityId, description)
                $userId = $arg1;
                $action = $arg2;
                $entityType = $arg3;
                $entityId = $arg4;
                $description = $arg5;
            } else {
                // (action, entityType, entityId, description)
                $userId = Auth::id();
                $action = $arg1;
                $entityType = $arg2;
                $entityId = $arg3;
                $description = $arg4;
            }

            return static::create([
                'user_id' => $userId ?: Auth::id(),
                'action' => $action ?? 'action',
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'description' => $description,
                'ip_address' => Request::ip() ?? '127.0.0.1',
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
