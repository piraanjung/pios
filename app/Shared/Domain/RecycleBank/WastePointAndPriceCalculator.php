<?php

namespace App\Shared\Domain\RecycleBank;

use InvalidArgumentException;

class WastePointAndPriceCalculator
{
    /**
     * Calculate price and points for recycle items.
     *
     * @param array $items Array of ['qty' => float|int, 'price_per_unit' => float, 'point_per_unit' => int]
     * @return array
     */
    public function calculate(array $items): array
    {
        $totalAmount = 0.0;
        $totalPoints = 0;
        $totalWeight = 0.0;
        $details = [];

        foreach ($items as $item) {
            $qty = (float) ($item['qty'] ?? 1);
            if ($qty < 0) {
                throw new InvalidArgumentException('Quantity cannot be negative.');
            }

            $pricePerUnit = (float) ($item['price_per_unit'] ?? $item['price'] ?? 0.0);
            $pointPerUnit = (int) ($item['point_per_unit'] ?? $item['point'] ?? 0);
            $weight = (float) ($item['weight'] ?? 0.0);

            $itemAmount = round($qty * $pricePerUnit, 2);
            $itemPoints = (int) round($qty * $pointPerUnit);

            $totalAmount += $itemAmount;
            $totalPoints += $itemPoints;
            $totalWeight += $weight;

            $details[] = array_merge($item, [
                'qty'          => $qty,
                'subtotal'     => $itemAmount,
                'total_points' => $itemPoints,
            ]);
        }

        return [
            'total_amount' => round($totalAmount, 2),
            'total_points' => $totalPoints,
            'total_weight' => round($totalWeight, 2),
            'items'        => $details,
        ];
    }
}
