<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'property_id', 'title', 'description', 'contractor_name', 'contractor_contact',
    'project_value', 'fee_percent', 'fee_amount', 'final_cost', 'status', 'approval_status',
    'approved_by', 'approved_at', 'start_date', 'expected_completion_date', 'actual_completion_date',
])]
class RenovationProject extends Model
{
    protected function casts(): array
    {
        return [
            'project_value' => 'decimal:2',
            'fee_percent' => 'decimal:2',
            'fee_amount' => 'decimal:2',
            'final_cost' => 'decimal:2',
            'approved_at' => 'datetime',
            'start_date' => 'date',
            'expected_completion_date' => 'date',
            'actual_completion_date' => 'date',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function milestones()
    {
        return $this->hasMany(RenovationMilestone::class)->orderBy('sort_order');
    }

    public function media()
    {
        return $this->hasMany(RenovationMedia::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function totalWithFee(): float
    {
        return (float) $this->project_value + (float) $this->fee_amount;
    }

    public function milestoneProgress(): int
    {
        $total = $this->milestones->count();

        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->milestones->where('status', 'completed')->count() / $total) * 100);
    }
}
