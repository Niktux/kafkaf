<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence;

use Niktux\Kafkaf\Domain\Collaborateur;
use Niktux\Kafkaf\Domain\CollaborateurCollection;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;

interface CollaborateurRepository
{
    public function find(string $uuid): ?Collaborateur;
    public function findAll(): CollaborateurCollection;

    public function save(DTO\Collaborateur $dto): void;
}
