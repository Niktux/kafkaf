<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

class AbsenceCollective implements Absence
{
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
        $period = new \DatePeriod($this->from, new \DateInterval('P1D'), $this->to->modify('+1 day'));

        return iterator_count($period);
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
}
