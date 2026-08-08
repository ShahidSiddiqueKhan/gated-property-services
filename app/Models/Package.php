<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'description', 'features', 'monthly_price', 'rent_commission_percent', 'is_active', 'sort_order'])]
class Package extends Model
{
    protected function casts(): array
    {
        return [
            'features' => 'array',
            'monthly_price' => 'decimal:2',
            'rent_commission_percent' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function propertyPackages()
    {
        return $this->hasMany(PropertyPackage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
