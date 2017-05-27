<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

abstract class AbstractAbsence implements Absence
{
    public function restrictToWeek(int $week): Absence
    {
        $fromWeek = (int) $this->from()->format('W');
        $toWeek = (int) $this->to()->format('W');

        if($week < $fromWeek || $week > $toWeek)
        {
            return new NullAbsence();
        }

        if($fromWeek === $toWeek)
        {
            return $this;
        }

        $period = new \DatePeriod(
                $this->from(),
                new \DateInterval('P1D'),
                $this->to()->modify('+1 day')
                );

        $start = null;
        $end = null;
        foreach($period as $day)
        {
            $dayWeek = (int) $day->format('W');

            if($dayWeek === $week)
            {
                $end = $day;

                if($start === null)
                {
                    $start = $day;
                }
            }
        }

        return $this->extractPart($start, $end);
    }

    abstract protected function extractPart(\DateTimeImmutable $start, \DateTimeImmutable $end);
}
