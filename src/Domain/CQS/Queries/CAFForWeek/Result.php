<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAFForWeek;

use Onyx\Services\CQS\QueryResult;
use Niktux\Kafkaf\Domain\Collaborateur;
use Niktux\Kafkaf\Domain\Absences\Filters\ExcludeAbsenceCollectiveFilterIterator;

final class Result implements QueryResult
{
    public
        $week,
        $dates,
        $collectives;

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
            'caf' => 5 - $absencesDuration,
            'totalAbsences' => $absencesDuration,
            'absencesPerso' => new ExcludeAbsenceCollectiveFilterIterator($absences->getIterator()),
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

    public function maxDuration(): int
    {
        $max = -1;
        foreach($this->absences as $absenceInfo)
        {
            $max = max($max, $absenceInfo['caf']);
        }

        return $max;
    }

    public function nbCollaborateurs(): int
    {
        return count($this->absences);
    }
}
