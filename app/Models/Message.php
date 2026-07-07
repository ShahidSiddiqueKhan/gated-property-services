<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'parent_id', 'subject', 'body', 'sender', 'status', 'is_read'])]
class Message extends Model
{
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id')->orderBy('created_at');
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }
}
