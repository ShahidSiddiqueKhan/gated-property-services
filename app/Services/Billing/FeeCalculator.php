<?php

namespace App\Services\Billing;

use App\Models\FeeTier;

/**
 * Single source of truth for every percentage/discount calculation used
 * across GATED's revenue model — subscription frequency discounts, rent
 * collection commission, and tiered maintenance/renovation management fees.
 * Kept deliberately dependency-light (plain arrays in/out) so it's easy to
 * unit test and easy to reuse from controllers, Blade views, and Artisan
 * commands alike.
 */
class FeeCalculator
{
    /**
     * Standard frequency discounts applied to a package's monthly price.
     * Quarterly bills 3 months' worth at a 5% discount; annually bills 12
     * months' worth at a 10% discount.
     */
    public const FREQUENCY_MONTHS = [
        'monthly' => 1,
        'quarterly' => 3,
        'annually' => 12,
    ];

    public const FREQUENCY_DISCOUNTS = [
        'monthly' => 0.0,
        'quarterly' => 5.0,
        'annually' => 10.0,
    ];

    /**
     * Resolve the price to bill for a package at a given frequency.
     *
     * @return array{months:int, discount_percent:float, base_price:float, gross_price:float, discount_amount:float, final_price:float}
     */
    public function frequencyPrice(float $monthlyPrice, string $frequency): array
    {
        $frequency = array_key_exists($frequency, self::FREQUENCY_MONTHS) ? $frequency : 'monthly';
        $months = self::FREQUENCY_MONTHS[$frequency];
        $discountPercent = self::FREQUENCY_DISCOUNTS[$frequency];

        $grossPrice = round($monthlyPrice * $months, 2);
        $discountAmount = round($grossPrice * ($discountPercent / 100), 2);
        $finalPrice = round($grossPrice - $discountAmount, 2);

        return [
            'months' => $months,
            'discount_percent' => $discountPercent,
            'base_price' => round($monthlyPrice, 2),
            'gross_price' => $grossPrice,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
        ];
    }

    /**
     * Split a rent payment into GATED's commission and the owner's net.
     *
     * @return array{rent_amount:float, commission_percent:float, commission_amount:float, owner_amount:float}
     */
    public function rentCommission(float $rentAmount, float $commissionPercent): array
    {
        $commissionPercent = max(0, min($commissionPercent, 100));
        $commissionAmount = round($rentAmount * ($commissionPercent / 100), 2);

        return [
            'rent_amount' => round($rentAmount, 2),
            'commission_percent' => $commissionPercent,
            'commission_amount' => $commissionAmount,
            'owner_amount' => round($rentAmount - $commissionAmount, 2),
        ];
    }

    /**
     * Contractor invoice → GATED's tiered maintenance coordination fee.
     *
     * @return array{contractor_cost:float, fee_percent:float, fee_amount:float, total:float}
     */
    public function maintenanceFee(float $contractorInvoice): array
    {
        return $this->tieredFee('maintenance', $contractorInvoice);
    }

    /**
     * Renovation project value → GATED's tiered project management fee.
     *
     * @return array{contractor_cost:float, fee_percent:float, fee_amount:float, total:float}
     */
    public function renovationFee(float $projectValue): array
    {
        return $this->tieredFee('renovation', $projectValue);
    }

    /**
     * @return array{contractor_cost:float, fee_percent:float, fee_amount:float, total:float}
     */
    protected function tieredFee(string $category, float $baseAmount): array
    {
        $tier = FeeTier::category($category)
            ->where('min_amount', '<=', $baseAmount)
            ->where(function ($q) use ($baseAmount) {
                $q->whereNull('max_amount')->orWhere('max_amount', '>', $baseAmount);
            })
            ->orderByDesc('min_amount')
            ->first();

        $feePercent = $tier ? (float) $tier->fee_percent : 0.0;
        $feeAmount = round($baseAmount * ($feePercent / 100), 2);

        return [
            'contractor_cost' => round($baseAmount, 2),
            'fee_percent' => $feePercent,
            'fee_amount' => $feeAmount,
            'total' => round($baseAmount + $feeAmount, 2),
        ];
    }

    /**
     * Tenant placement fee as a percentage of one month's rent (typically
     * agreed per-client between 50–100%).
     *
     * @return array{monthly_rent:float, fee_percent:float, fee_amount:float}
     */
    public function tenantPlacementFee(float $monthlyRent, float $feePercent): array
    {
        $feePercent = max(0, min($feePercent, 100));

        return [
            'monthly_rent' => round($monthlyRent, 2),
            'fee_percent' => $feePercent,
            'fee_amount' => round($monthlyRent * ($feePercent / 100), 2),
        ];
    }
}
