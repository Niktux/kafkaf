<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\Filters;

use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Domain\Absences\NullAbsence;

class AbsenceForWeek
{
    public static function filter(AbsenceCollection $collection, int $week)
    {
        $filteredCollection = new AbsenceCollection();

        foreach($collection as $absence)
        {
            $filtered = $absence->restrictToWeek($week);

            if(! $filtered instanceof NullAbsence)
            {
                $filteredCollection->addAbsence($filtered);
            }
        }

        return $filteredCollection;
    }
}
