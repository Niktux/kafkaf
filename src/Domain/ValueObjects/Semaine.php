<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\ValueObjects;

use Puzzle\Pieces\ConvertibleToString;

final class Semaine implements ConvertibleToString
{
    private
        $year,
        $weekNumber;

    public function __construct(int $weekNumber, ?int $year = null)
    {
        if($year === null)
        {
            $year = (int) date('Y');
        }

        $this->year = $year;
        $this->weekNumber = $weekNumber;
    }

    public static function fromString(string $yearDashWeek): self
    {
        if(preg_match('~(?P<year>\d{4})\-(?P<week>\d{1,2})~', $yearDashWeek, $matches))
        {
            $year = $matches['year'];
            $week = $matches['week'];

            if($week >= 1 && $week <= 53)
            {
                return new self($week, $year);
            }
        }

        throw new \InvalidArgumentException("$yearDashWeek is not a valid week descriptor");
    }

    public function getNumber(): int
    {
        return $this->weekNumber;
    }

    public function __toString(): string
    {
        return sprintf("%d-%d", $this->year, $this->weekNumber);
    }

    public function getDescription(): string
    {
        $f = 'd-m-Y';

        return sprintf(
            "du %s au %s",
            $this->getFirstDay()->format($f),
            $this->getLastDay()->format($f)
        );
    }

    public function getFirstDay(): \DateTimeImmutable
    {
        $d = new \DateTime();
        $d->setISODate($this->year, $this->weekNumber, 1);
        $d->setTime(0, 0);

        return \DateTimeImmutable::createFromMutable($d);
    }

    public function getLastDay(): \DateTimeImmutable
    {
        $d = new \DateTime();
        $d->setISODate($this->year, $this->weekNumber, 5);
        $d->setTime(23, 59, 59);

        return \DateTimeImmutable::createFromMutable($d);
    }
}
