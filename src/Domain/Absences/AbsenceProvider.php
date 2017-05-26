<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

interface AbsenceProvider
{
    public function list(int $weekFrom, int $weekTo): AbsenceCollection;
}
