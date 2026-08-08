<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['renovation_project_id', 'title', 'description', 'status', 'due_date', 'completed_at', 'sort_order'])]
class RenovationMilestone extends Model
{
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(RenovationProject::class, 'renovation_project_id');
    }
}
