<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

use Niktux\Kafkaf\Services\WorkingDayAware;

class AbsenceCollective extends AbstractAbsence implements Absence
{
    use WorkingDayAware;

    private const DATE_FORMAT = 'd-m-Y';

    private
        $from,
        $to,
        $description;

    public function __construct(\DateTimeImmutable $from, \DateTimeImmutable $to, string $description)
    {
        $this->from = $from;
        $this->to = $to;
        $this->description = $description;
    }

    public function from(): \DateTimeImmutable
    {
        return $this->from;
    }

    public function to(): \DateTimeImmutable
    {
        return $this->to;
    }

    public function duration(): int
    {
        $period = new \DatePeriod($this->from(), new \DateInterval('P1D'), $this->to()->modify('+1 day'));
        $days = 0;

        foreach($period as $date)
        {
            if($this->isAWorkingDay($date))
            {
                $days++;
            }
        }

        return $days;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function __toString()
    {
        return sprintf(
            "Absence collective du %s au %s (%s)",
            $this->from->format(self::DATE_FORMAT),
            $this->to->format(self::DATE_FORMAT),
            $this->description
        );
    }

    /**
     * Suppose That AbsenceCollective are always 1 or 2 days (else must return AbsenceCollection)
     */
    public function excludeFrom(Absence $absence): Absence
    {
        // No overlap
        if($absence->from() > $this->to || $absence->to() < $this->from)
        {
            return $this;
        }

        // Full inclusion
        if($absence->from() <= $this->from && $absence->to() >= $this->to)
        {
            return new NullAbsence();
        }

        if($this->from < $absence->from())
        {
            return new self($this->from, $absence->from()->modify('-1 day'), "Part of " . $this->description());
        }

        return new self($absence->to()->modify('+1 day'), $this->to, "Part of " . $this->description());
    }

    protected function extractPart(\DateTimeImmutable $start, \DateTimeImmutable $end)
    {
        return new self($start, $end, $this->description());
    }
}
