<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\AbsenceProviders;

use Niktux\Kafkaf\Domain\Absences\AbsenceProvider;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollective;

class Naomix implements AbsenceProvider
{
    private
        $naomix,
        $bts,
        $currentYear;

    public function __construct(?int $currentYear = null)
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
    }

    public function list(int $weekFrom, int $weekTo): AbsenceCollection
    {
        $collection = new AbsenceCollection();

        $naomixWeeks = $this->naomix[$this->currentYear];
        $btsWeek = $this->bts[$this->currentYear];

        for($w = $weekFrom; $w <= $weekTo; $w++)
        {
            if($w % 4 === $naomixWeeks)
            {
                $shift = $w === $btsWeek ? 1 : 0;

                $dti = new \DateTimeImmutable();
                $from = $dti->setISODate($this->currentYear, $w, 4 - $shift);
                $to = $dti->setISODate($this->currentYear, $w, 5 - $shift);

                $collection->addAbsence(new AbsenceCollective($from, $to, 'Naomix'));
            }

            if($w === $btsWeek)
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
