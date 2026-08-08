<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code', 'type', 'region', 'icon', 'instructions', 'is_active', 'sort_order'])]
class PaymentMethod extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function isGateway(): bool
    {
        return $this->type === 'gateway';
    }
}
