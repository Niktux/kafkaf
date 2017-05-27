<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\AbsenceProviders;

use Niktux\Kafkaf\Domain\Absences\AbsenceProvider;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollective;
use Niktux\Kafkaf\Services\WorkingDayAware;
use Niktux\Kafkaf\Domain\Absences\JoursFeriesProvider;

class JoursFeries implements AbsenceProvider, JoursFeriesProvider
{
    use WorkingDayAware;

    private
        $currentYear;

    public function __construct(?int $currentYear = null)
    {
        if($currentYear === null)
        {
            $currentYear = (int) date('Y');
        }

        $this->currentYear = $currentYear;
    }

    private function getForYear(?int $year)
    {
        if($year === null)
        {
            $year = (int) date('Y');
        }

        $easterDate = \easter_date($year);
        $easterDay = (int) date('j', $easterDate);
        $easterMonth = (int) date('n', $easterDate);

        $timestamps = [
            // Dates fixes
            mktime(0, 0, 0, 1,  1,  $year),  // 1er janvier
            mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
            mktime(0, 0, 0, 5,  8,  $year),  // Victoire des alliés
            mktime(0, 0, 0, 7,  14, $year),  // Fête nationale
            mktime(0, 0, 0, 8,  15, $year),  // Assomption
            mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
            mktime(0, 0, 0, 11, 11, $year),  // Armistice
            mktime(0, 0, 0, 12, 25, $year),  // Noel

            // Dates variables
            mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $year),
            mktime(0, 0, 0, $easterMonth, $easterDay + 39, $year),
            mktime(0, 0, 0, $easterMonth, $easterDay + 50, $year),
        ];

        $days = [];

        foreach($timestamps as $ts)
        {
            $days[] = \DateTimeImmutable::createFromFormat('U', (string) $ts);
        }

        return $days;

    }

    public function list(int $weekFrom, int $weekTo): AbsenceCollection
    {
        $collection = new AbsenceCollection();

        foreach($this->getForYear($this->currentYear) as $day)
        {
            $week = (int) $day->format('W');

            if(($week >= $weekFrom && $week <= $weekTo) && $this->isAWorkingDay($day))
            {
                $collection->addAbsence(new AbsenceCollective($day, $day, "Jour férié"));
            }
        }

        return $collection;
    }

    public function isFerie(\DateTimeImmutable $day): bool
    {
        $days = $this->getForYear($this->currentYear);
        $lookingFor = $day->format('Y-m-d');

        foreach($days as $ferie)
        {
            if($ferie->format('Y-m-d') === $lookingFor)
            {
                return true;
            }
        }

        return false;
    }
}
