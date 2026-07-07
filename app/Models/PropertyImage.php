<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['property_id', 'path', 'is_cover', 'sort_order'])]
class PropertyImage extends Model
{
    protected function casts(): array
    {
        return [
            'is_cover' => 'boolean',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
