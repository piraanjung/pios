<?php

namespace Tests\Unit\Features\Waterworks;

use App\Shared\Domain\Waterworks\TieredWaterPricingCalculator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TieredWaterPricingCalculatorTest extends TestCase
{
    protected TieredWaterPricingCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new TieredWaterPricingCalculator();
    }

    public function test_rejects_negative_water_units(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->calculator->calculate(-5);
    }

    public function test_zero_usage_charges_only_reserve_meter_plus_vat(): void
    {
        $result = $this->calculator->calculate(
            waterUsed: 0,
            tiers: [
                ['min_units' => 0, 'max_units' => 10, 'rate_per_unit' => 5.0],
            ],
            reserveMeter: 10.0,
            vatPercent: 7.0
        );

        $this->assertEquals(0.0, $result['water_charge']);
        $this->assertEquals(10.0, $result['reserve_meter']);
        $this->assertEquals(10.0, $result['subtotal']);
        $this->assertEquals(0.70, $result['vat_amount']);
        $this->assertEquals(10.70, $result['total_paid']);
    }

    /**
     * @dataProvider progressiveTierDataProvider
     */
    public function test_progressive_tiered_calculation(
        int|float $waterUsed,
        float $reserveMeter,
        float $vatPercent,
        float $expectedWaterCharge,
        float $expectedTotalPaid
    ): void {
        $tiers = [
            ['min_units' => 0, 'max_units' => 10, 'rate_per_unit' => 5.0],   // 0-10 = 10 units @ 5.0 = 50.0
            ['min_units' => 11, 'max_units' => 20, 'rate_per_unit' => 8.0], // 11-20 = 10 units @ 8.0 = 80.0
            ['min_units' => 21, 'max_units' => null, 'rate_per_unit' => 12.0], // > 20 @ 12.0
        ];

        $result = $this->calculator->calculate(
            waterUsed: $waterUsed,
            tiers: $tiers,
            reserveMeter: $reserveMeter,
            vatPercent: $vatPercent,
            calculationType: 1
        );

        $this->assertEquals($expectedWaterCharge, $result['water_charge']);
        $this->assertEquals($expectedTotalPaid, $result['total_paid']);
    }

    public static function progressiveTierDataProvider(): array
    {
        return [
            'exact tier 1 limit (10 units)' => [10, 0.0, 0.0, 50.0, 50.0],
            'middle of tier 2 (15 units)'   => [15, 10.0, 0.0, 90.0, 100.0], // (10*5)+(5*8) = 50+40 = 90
            'tier 3 boundary (25 units)'    => [25, 0.0, 7.0, 190.0, 203.30], // 50 + 80 + (5*12) = 190, VAT=13.30, total=203.30
        ];
    }

    public function test_flat_bracket_calculation(): void
    {
        $tiers = [
            ['min_units' => 0, 'max_units' => 10, 'rate_per_unit' => 5.0],
            ['min_units' => 11, 'max_units' => 20, 'rate_per_unit' => 7.0],
            ['min_units' => 21, 'max_units' => null, 'rate_per_unit' => 10.0],
        ];

        // 15 units falls into 11-20 bracket: all 15 units charged @ 7.0 = 105.0
        $result = $this->calculator->calculate(
            waterUsed: 15,
            tiers: $tiers,
            reserveMeter: 20.0,
            vatPercent: 7.0,
            calculationType: 2
        );

        $this->assertEquals(105.0, $result['water_charge']);
        $this->assertEquals(125.0, $result['subtotal']);
        $this->assertEquals(8.75, $result['vat_amount']);
        $this->assertEquals(133.75, $result['total_paid']);
    }
}
