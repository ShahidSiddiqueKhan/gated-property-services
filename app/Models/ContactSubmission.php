<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'phone', 'subject', 'message', 'type', 'is_handled', 'preferred_at'])]
class ContactSubmission extends Model
{
    protected function casts(): array
    {
        return [
            'is_handled' => 'boolean',
            'preferred_at' => 'datetime',
        ];
    }
}
