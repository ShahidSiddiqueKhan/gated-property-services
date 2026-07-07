<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request as RequestFacade;

#[Fillable(['user_id', 'action', 'subject_type', 'subject_id', 'description', 'ip_address'])]
class AuditLog extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an admin/system action for the audit trail.
     */
    public static function record(?User $user, string $action, mixed $subject = null, ?string $description = null): self
    {
        return static::create([
            'user_id' => $user?->id,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'description' => $description,
            'ip_address' => RequestFacade::ip(),
        ]);
    }
}
