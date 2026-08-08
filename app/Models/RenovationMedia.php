<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['renovation_project_id', 'type', 'phase', 'path', 'caption'])]
class RenovationMedia extends Model
{
    public function project()
    {
        return $this->belongsTo(RenovationProject::class, 'renovation_project_id');
    }
}
