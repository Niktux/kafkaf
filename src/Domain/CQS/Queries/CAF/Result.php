<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAF;

use Onyx\Services\CQS\QueryResult;
use Niktux\Kafkaf\Domain\Collaborateur;

final class Result implements QueryResult
{
    private
        $absences;

    public function __construct()
    {
        $this->absences = [];
    }

    public function add(Collaborateur $collaborateur, iterable $absences): self
    {
        $this->absences[$collaborateur->uuid()] = [
            'collaborateur' => $collaborateur,
            'absences' => $absences,
        ];

        return $this;
    }


    public function getAbsences(): iterable
    {
        return $this->absences;
    }
}
