<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAFForWeek;

use Onyx\Services\CQS\QueryResult;
use Niktux\Kafkaf\Domain\Collaborateur;

final class Result implements QueryResult
{
    public
        $week,
        $dates;

    private
        $absences;

    public function __construct()
    {
        $this->absences = [];
    }

    public function add(Collaborateur $collaborateur, iterable $absences): self
    {
        $absencesDuration = 0;
        foreach($absences as $absence)
        {
            $absencesDuration += $absence->duration();
        }

        $this->absences[$collaborateur->uuid()] = [
            'collaborateur' => $collaborateur,
            'absences' => $absences,
            'caf' => 5 - $absencesDuration
        ];

        return $this;
    }

    public function getAbsences(): iterable
    {
        return $this->absences;
    }

    public function getTotalCAF(): int
    {
        $caf = 0;

        foreach($this->absences as $absenceInfo)
        {
            $caf += $absenceInfo['caf'];
        }

        return $caf;
    }
}
