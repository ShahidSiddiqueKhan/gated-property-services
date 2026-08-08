<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['category', 'name', 'description', 'price', 'price_max', 'is_active', 'sort_order'])]
class ServiceCatalogItem extends Model
{
    protected $table = 'service_catalog';

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_max' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function priceLabel(): string
    {
        if ($this->price_max && (float) $this->price_max > (float) $this->price) {
            return 'PKR ' . number_format((float) $this->price) . ' – ' . number_format((float) $this->price_max);
        }

        return 'PKR ' . number_format((float) $this->price);
    }
}
