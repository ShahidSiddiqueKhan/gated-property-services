<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'discount_label', 'description', 'valid_until', 'is_active'])]
class Promotion extends Model
{
    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now()->toDateString());
            });
    }
}
