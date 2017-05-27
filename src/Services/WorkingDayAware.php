<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Services;

trait WorkingDayAware
{
    private function isAWorkingDay(\DateTimeImmutable $day): bool
    {
        // constants are not allowed in traits
        $daysOff = [6 /*SATURDAY*/, 0 /*SUNDAY*/];

        $dayInweek = $day->format('w');

        return ! in_array($dayInweek, $daysOff);
    }
}
