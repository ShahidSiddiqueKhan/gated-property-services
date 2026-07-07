<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'location', 'content', 'rating', 'avatar', 'is_featured'])]
class Testimonial extends Model
{
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }
}
