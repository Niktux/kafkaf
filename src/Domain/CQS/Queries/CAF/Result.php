<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAF;

use Onyx\Services\CQS\QueryResult;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;

/**
 * @immutable
 */
final class Result implements QueryResult
{
    private
        $absences;

    public function __construct(AbsenceCollection $absences)
    {
        $this->absences = $absences;
    }

    public function getAbsences(): AbsenceCollection
    {
        return $this->absences;
    }
}
