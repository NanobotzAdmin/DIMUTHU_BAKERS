<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\DeliveryDateHelper;
use Carbon\Carbon;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class DeliveryDateHelperTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function order_placed_before_1pm_schedules_next_working_day_8am()
    {
        // Mock Holiday model to return no holidays
        $holidayMock = \Mockery::mock('alias:App\Models\Holiday');
        $holidayMock->shouldReceive('pluck')
            ->with('date')
            ->andReturn(collect([]));

        // Placed Monday June 15, 2026, 11:00 AM (Before 1 PM)
        $orderTime = Carbon::parse('2026-06-15 11:00:00');
        
        $deliveryDate = DeliveryDateHelper::calculateDeliveryDate($orderTime);
        
        // Expected: Tuesday June 16, 2026, 8:00 AM
        $this->assertEquals('2026-06-16 08:00:00', $deliveryDate->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function order_placed_after_1pm_schedules_day_after_next_working_day_10am()
    {
        $holidayMock = \Mockery::mock('alias:App\Models\Holiday');
        $holidayMock->shouldReceive('pluck')
            ->with('date')
            ->andReturn(collect([]));

        // Placed Monday June 15, 2026, 2:00 PM (After 1 PM)
        $orderTime = Carbon::parse('2026-06-15 14:00:00');
        
        $deliveryDate = DeliveryDateHelper::calculateDeliveryDate($orderTime);
        
        // Expected: Wednesday June 17, 2026, 10:00 AM
        $this->assertEquals('2026-06-17 10:00:00', $deliveryDate->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function calculation_skips_sundays()
    {
        $holidayMock = \Mockery::mock('alias:App\Models\Holiday');
        $holidayMock->shouldReceive('pluck')
            ->with('date')
            ->andReturn(collect([]));

        // Placed Saturday June 20, 2026, 11:00 AM
        // Before 1 PM -> Target next day is Sunday June 21, but Sunday is skipped.
        // Expected delivery: Monday June 22, 2026, 8:00 AM
        $orderTime = Carbon::parse('2026-06-20 11:00:00');
        
        $deliveryDate = DeliveryDateHelper::calculateDeliveryDate($orderTime);
        
        $this->assertEquals('2026-06-22 08:00:00', $deliveryDate->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function calculation_skips_configured_holidays()
    {
        $holidayMock = \Mockery::mock('alias:App\Models\Holiday');
        $holidayMock->shouldReceive('pluck')
            ->with('date')
            ->andReturn(collect(['2026-06-16']));

        // Placed Monday June 15, 2026, 11:00 AM
        // Before 1 PM -> Target next day is Tuesday June 16, but it is a holiday.
        // Expected delivery: Wednesday June 17, 2026, 8:00 AM
        $orderTime = Carbon::parse('2026-06-15 11:00:00');
        
        $deliveryDate = DeliveryDateHelper::calculateDeliveryDate($orderTime);
        
        $this->assertEquals('2026-06-17 08:00:00', $deliveryDate->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function is_holiday_or_sunday_identifies_sundays_and_configured_holidays()
    {
        $holidayMock = \Mockery::mock('alias:App\Models\Holiday');
        $holidayMock->shouldReceive('where')
            ->andReturnUsing(function($column, $value) {
                $exists = ($value === '2026-06-17');
                $builderMock = \Mockery::mock('Builder');
                $builderMock->shouldReceive('exists')->andReturn($exists);
                return $builderMock;
            });

        // Sunday June 21, 2026
        $this->assertTrue(DeliveryDateHelper::isHolidayOrSunday('2026-06-21'));
        
        // Wednesday June 17, 2026 (Configured Holiday)
        $this->assertTrue(DeliveryDateHelper::isHolidayOrSunday('2026-06-17'));

        // Monday June 15, 2026 (Regular day)
        $this->assertFalse(DeliveryDateHelper::isHolidayOrSunday('2026-06-15'));
    }
}
