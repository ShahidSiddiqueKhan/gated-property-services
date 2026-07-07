<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'ticket_no', 'property_id', 'user_id', 'title', 'category', 'description',
    'priority', 'status', 'assigned_to', 'estimated_completion', 'completed_at',
])]
class MaintenanceRequest extends Model
{
    protected function casts(): array
    {
        return [
            'estimated_completion' => 'date',
            'completed_at' => 'datetime',
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
}
