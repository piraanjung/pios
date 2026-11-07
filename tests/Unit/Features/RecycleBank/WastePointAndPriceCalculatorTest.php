<?php

namespace Tests\Unit\Features\RecycleBank;

use App\Shared\Domain\RecycleBank\WastePointAndPriceCalculator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class WastePointAndPriceCalculatorTest extends TestCase
{
    protected WastePointAndPriceCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new WastePointAndPriceCalculator();
    }

    public function test_rejects_negative_quantity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->calculator->calculate([
            ['qty' => -2, 'price' => 5.0, 'point' => 10],
        ]);
    }

    public function test_empty_items_returns_zero_totals(): void
    {
        $result = $this->calculator->calculate([]);

        $this->assertEquals(0.0, $result['total_amount']);
        $this->assertEquals(0, $result['total_points']);
        $this->assertEquals(0.0, $result['total_weight']);
        $this->assertEmpty($result['items']);
    }

    public function test_calculates_multiple_recycle_items(): void
    {
        $items = [
            ['name' => 'ขวดพลาสติก PET', 'qty' => 10, 'price' => 0.50, 'point' => 1, 'weight' => 0.25], // 5.00 THB, 10 pts
            ['name' => 'กระป๋องอะลูมิเนียม', 'qty' => 4, 'price' => 1.25, 'point' => 3, 'weight' => 0.08], // 5.00 THB, 12 pts
        ];

        $result = $this->calculator->calculate($items);

        $this->assertEquals(10.00, $result['total_amount']);
        $this->assertEquals(22, $result['total_points']);
        $this->assertEquals(0.33, $result['total_weight']);
        $this->assertCount(2, $result['items']);
        $this->assertEquals(5.00, $result['items'][0]['subtotal']);
        $this->assertEquals(10, $result['items'][0]['total_points']);
    }
}
