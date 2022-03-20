<?php
/*
 * File name: SalonTest.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace Models;

use App\Models\Salon;
use Carbon\Carbon;
use Tests\TestCase;

class SalonTest extends TestCase
{

    public function testGetAvailableAttribute()
    {
        $salon = Salon::find(17);
        $this->assertTrue($salon->available);
        $this->assertTrue($salon->accepted);
        $this->assertTrue($salon->openingHours()->isOpenAt(new Carbon('2021-02-05 12:00:00')));
    }

    public function testOpeningHours()
    {
        $salon = Salon::find(17);
        $open = $salon->openingHours()->isOpenAt(new Carbon('2021-02-05 12:00:00'));
        $this->assertTrue($open);
    }

    public function testWeekCalendar()
    {
        $salon = Salon::find(17);
        $dates = $salon->weekCalendarRange(Carbon::now(), 1);
        $this->assertIsArray($dates);
    }

    public function testSalonReview()
    {
        $salon = Salon::find(7);
        $reviews = $salon->salonReviews()->get();
        $this->assertTrue(true);
    }

    public function testGetTotalReviewsAttribute()
    {
        $salon = Salon::find(17);
        $total = $salon->getTotalReviewsAttribute();
        $this->assertEquals(0, $total);
    }
}
