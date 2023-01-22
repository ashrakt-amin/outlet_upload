<?php
namespace App\Http\Traits;

use Carbon\Carbon;

Trait TimeTricksTrait {

    public function timeFunction()
    {
        $time = now();
        $seconds             = $time->seconds();
        $minute              = $time->minute();
        $hours               = $time->hours();
        $month               = $time->month();
        $monthName           = $time->monthName();
        $dayOfWeek           = $time->dayOfWeek();
        $weekNumberOfMonth   = $time->weekNumberOfMonth();
        $weekOfYear          = $time->weekOfYear();
        $shortLocalDayOfWeek = $time->shortLocalDayOfWeek();
        $startOfHour         = $time->startOfHour();
        $startOfHour2        = $time->copy()->startOfHour();
        $endOfMonth          = $time->endOfMonth();
        $endOfMonth2         = $time->copy()->endOfMonth();
        $nextSunday          = $time->next('sunday');
        $nextWeekDay         = $time->nextWeekday();
        $nextWeekendDay      = $time->nextWeekendDay();
        $secondSundayOfMonth = $time->nthOfMonth(2, Carbon::SUNDAY);
        $endOfMonth          = $time->endOfMonth();
        $average             = $time->average($endOfMonth);
        $addMonth            = $time->addMonth();
        $addHours            = $time->addHours(24); // 11:59:59
        $addHours            = $time->subHours(24); // 23:59:59
        $subYears            = $time->subYears(2);
        $subWeekDays         = $time->subWeekDays(4);
        $diffInHours         = $time->diffInHours($time);
        $difInWeekDays       = now()->subDay()->diffInDays(now()->subMonths(2));
        $difInForHumans      = now()->subDay()->diffForHumans(now()->subMonths(2)->now()->subdays(2));
        $difInForHumansAdd   = now()->subDay()->diffForHumans(now()->addDays(2));

        return $diffInHours;
    }

    public function timeDiffInDays($day)
    {
        $time = now();
        $diffInDays = now()->addDays($day);
        return $diffInDays;
    }
}
