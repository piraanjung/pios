<?php

namespace App\Shared\Domain\Waterworks;

use InvalidArgumentException;

class TieredWaterPricingCalculator
{
    /**
     * Calculate water charge, maintenance fee, vat, and total billing.
     *
     * @param int|float $waterUsed
     * @param array $tiers Array of ['min_units' => int, 'max_units' => ?int, 'rate_per_unit' => float]
     * @param float $reserveMeter Maintenance/meter reservation fee
     * @param float $vatPercent VAT percentage (e.g., 7.0)
     * @param int $calculationType 1 = Progressive Tiered, 2 = Flat Bracket
     * @return array
     */
    public function calculate(
        int|float $waterUsed,
        array $tiers = [],
        float $reserveMeter = 0.0,
        float $vatPercent = 0.0,
        int $calculationType = 1
    ): array {
        if ($waterUsed < 0) {
            throw new InvalidArgumentException('Water units used cannot be negative.');
        }

        $waterCharge = 0.0;

        if ($waterUsed > 0 && !empty($tiers)) {
            if ($calculationType === 2) {
                // Flat Bracket
                $matchedRate = 0.0;
                foreach ($tiers as $tier) {
                    $min = (float) ($tier['min_units'] ?? 0);
                    $max = isset($tier['max_units']) && $tier['max_units'] !== null ? (float) $tier['max_units'] : PHP_FLOAT_MAX;
                    if ($waterUsed >= $min && $waterUsed <= $max) {
                        $matchedRate = (float) ($tier['rate_per_unit'] ?? 0);
                        break;
                    }
                }
                $waterCharge = $waterUsed * $matchedRate;
            } else {
                // Progressive Tiered (Step-by-step accumulation)
                $remainingUnits = (float) $waterUsed;

                foreach ($tiers as $tier) {
                    if ($remainingUnits <= 0) {
                        break;
                    }

                    $minUnits = (float) ($tier['min_units'] ?? 0);
                    $maxUnits = isset($tier['max_units']) && $tier['max_units'] !== null ? (float) $tier['max_units'] : PHP_FLOAT_MAX;
                    $ratePerUnit = (float) ($tier['rate_per_unit'] ?? 0);

                    $tierCapacity = ($minUnits == 0) ? $maxUnits : ($maxUnits - $minUnits + 1);
                    $unitsInTier = min($remainingUnits, $tierCapacity);

                    $waterCharge += ($unitsInTier * $ratePerUnit);
                    $remainingUnits -= $unitsInTier;
                }
            }
        }

        $subtotal = round($waterCharge + $reserveMeter, 2);
        $vatAmount = round(($subtotal * $vatPercent) / 100, 2);
        $totalPaid = round($subtotal + $vatAmount, 2);

        return [
            'water_used'    => $waterUsed,
            'water_charge'  => round($waterCharge, 2),
            'reserve_meter' => round($reserveMeter, 2),
            'subtotal'      => $subtotal,
            'vat_percent'   => $vatPercent,
            'vat_amount'    => $vatAmount,
            'total_paid'    => $totalPaid,
        ];
    }
}
