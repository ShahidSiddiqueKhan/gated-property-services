<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'property_id', 'package_id', 'frequency', 'base_price', 'discount_percent', 'final_price',
    'commission_percent', 'commission_overridden', 'status', 'started_at', 'renews_at',
    'cancelled_at', 'notes', 'created_by',
])]
class PropertyPackage extends Model
{
    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'final_price' => 'decimal:2',
            'commission_percent' => 'decimal:2',
            'commission_overridden' => 'boolean',
            'started_at' => 'date',
            'renews_at' => 'date',
            'cancelled_at' => 'date',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRenewingWithin($query, int $days)
    {
        return $query->active()
            ->whereNotNull('renews_at')
            ->whereBetween('renews_at', [now()->toDateString(), now()->addDays($days)->toDateString()]);
    }
}
