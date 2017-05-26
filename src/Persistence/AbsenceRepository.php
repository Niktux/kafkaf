<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence;

use Niktux\Kafkaf\Domain\Absences as Domain;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;

interface AbsenceRepository
{
    public function find(string $uuid): ?Domain\Absence;

    public function save(DTO\Absence $dto): void;
}
