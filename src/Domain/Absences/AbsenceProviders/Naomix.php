<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\AbsenceProviders;

use Niktux\Kafkaf\Domain\Absences\AbsenceProvider;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollective;
use Niktux\Kafkaf\Domain\Absences\JoursFeriesProvider;

class Naomix implements AbsenceProvider
{
    private
        $joursFeries,
        $naomix,
        $bts,
        $currentYear;

    public function __construct(JoursFeriesProvider $joursFeries, ?int $currentYear = null)
    {
        $this->naomix = [
            2017 => 1,
        ];
        $this->bts = [
            2017 => 37,
        ];

        if($currentYear === null)
        {
            $currentYear = (int) date('Y');
        }

        $this->currentYear = $currentYear;
        $this->joursFeries = $joursFeries;
    }

    private function isNaomixWeek(int $week): bool
    {
        $naomixWeeks = $this->naomix[$this->currentYear];

        return $week % 4 === $naomixWeeks;
    }

    private function isBTSWeek(int $week): bool
    {
        return $week === $this->bts[$this->currentYear];
    }

    private function shiftNaomixIfFerie(\DateTimeImmutable $from, \DateTimeImmutable $to): array
    {
        $modify = null;
        if($this->joursFeries->isFerie($from))
        {
            $modify = "-2 days";
        }
        else if($this->joursFeries->isFerie($to))
        {
            $modify = "-1 day";
        }

        if($modify !== null)
        {
            $from = $from->modify($modify);
            $to = $to->modify($modify);
        }

        return [$from, $to];
    }

    public function list(int $weekFrom, int $weekTo): AbsenceCollection
    {
        $collection = new AbsenceCollection();

        for($w = $weekFrom; $w <= $weekTo; $w++)
        {
            if($this->isNaomixWeek($w))
            {
                $shift = $this->isBTSWeek($w) ? 1 : 0;

                $dti = new \DateTimeImmutable();
                $from = $dti->setISODate($this->currentYear, $w, 4 - $shift);
                $to = $dti->setISODate($this->currentYear, $w, 5 - $shift);

                list($from, $to) = $this->shiftNaomixIfFerie($from, $to);

                $collection->addAbsence(new AbsenceCollective($from, $to, 'Naomix'));
            }

            if($this->isBTSWeek($w))
            {
                $dti = new \DateTimeImmutable();
                $from = $dti->setISODate($this->currentYear, $w, 5);
                $to = $dti->setISODate($this->currentYear, $w, 5);

                $collection->addAbsence(new AbsenceCollective($from, $to, 'BTS ' . $this->currentYear));
            }
        }

        return $collection;
    }
}
