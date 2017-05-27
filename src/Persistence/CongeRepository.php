<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence;

use Niktux\Kafkaf\Domain\Absences as Domain;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;

interface CongeRepository
{
    public function find(string $uuid): ?Domain\Conge;
    public function findByPeriod(\DateTimeImmutable $from, \DateTimeImmutable $to): AbsenceCollection;

    public function save(DTO\Conge $dto): void;
}
