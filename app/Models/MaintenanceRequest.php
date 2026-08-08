<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'ticket_no', 'property_id', 'user_id', 'title', 'category', 'description',
    'priority', 'status', 'assigned_to', 'estimated_completion', 'completed_at',
    'contractor_cost', 'gated_fee_percent', 'gated_fee_amount', 'total_cost',
    'invoice_path', 'payment_id',
])]
class MaintenanceRequest extends Model
{
    protected function casts(): array
    {
        return [
            'estimated_completion' => 'date',
            'completed_at' => 'datetime',
            'contractor_cost' => 'decimal:2',
            'gated_fee_percent' => 'decimal:2',
            'gated_fee_amount' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(MaintenanceImage::class);
    }

    public function updates()
    {
        return $this->hasMany(MaintenanceUpdate::class)->orderBy('created_at');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function isBilled(): bool
    {
        return ! is_null($this->total_cost);
    }
}
