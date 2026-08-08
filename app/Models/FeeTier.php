<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['category', 'min_amount', 'max_amount', 'fee_percent', 'sort_order'])]
class FeeTier extends Model
{
    protected function casts(): array
    {
        return [
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'fee_percent' => 'decimal:2',
        ];
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category)->orderBy('min_amount');
    }
}
