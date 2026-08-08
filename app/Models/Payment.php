<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'invoice_no', 'user_id', 'property_id', 'lease_id', 'type', 'revenue_stream', 'amount', 'status',
    'method', 'due_date', 'paid_date', 'receipt_path', 'notes',
    'gateway', 'gateway_reference', 'gateway_currency', 'gateway_payload',
    'base_amount', 'fee_percent', 'owner_amount', 'property_package_id', 'renovation_project_id',
])]
class Payment extends Model
{
    /**
     * Revenue stream classifications used for financial reporting. `type`
     * stays as the older broad category (rent/service/invoice/maintenance)
     * for backward compatibility with existing filters/views.
     */
    public const STREAM_PACKAGE_FEE = 'package_fee';

    public const STREAM_TENANT_PLACEMENT = 'tenant_placement';

    public const STREAM_RENT_COMMISSION = 'rent_commission';

    public const STREAM_MAINTENANCE_FEE = 'maintenance_fee';

    public const STREAM_RENOVATION_FEE = 'renovation_fee';

    public const STREAM_EMERGENCY_SERVICE = 'emergency_service';

    public const STREAM_ADVERTISING = 'advertising';

    public const STREAM_INSPECTION_REPORT = 'inspection_report';

    public static function streamLabels(): array
    {
        return [
            self::STREAM_PACKAGE_FEE => 'Package Fee',
            self::STREAM_TENANT_PLACEMENT => 'Tenant Placement',
            self::STREAM_RENT_COMMISSION => 'Rent Collection Commission',
            self::STREAM_MAINTENANCE_FEE => 'Maintenance Management Fee',
            self::STREAM_RENOVATION_FEE => 'Renovation Project Fee',
            self::STREAM_EMERGENCY_SERVICE => 'Emergency Call-out',
            self::STREAM_ADVERTISING => 'Property Advertising',
            self::STREAM_INSPECTION_REPORT => 'Inspection Report',
        ];
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'base_amount' => 'decimal:2',
            'fee_percent' => 'decimal:2',
            'owner_amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_date' => 'date',
            'gateway_payload' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function propertyPackage()
    {
        return $this->belongsTo(PropertyPackage::class);
    }

    public function renovationProject()
    {
        return $this->belongsTo(RenovationProject::class);
    }

    public function streamLabel(): string
    {
        return self::streamLabels()[$this->revenue_stream] ?? ucfirst(str_replace('_', ' ', (string) $this->type));
    }
}
