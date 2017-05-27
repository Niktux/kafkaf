<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence;

use Niktux\Kafkaf\Domain\Collaborateur;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;

interface CollaborateurRepository extends ReadableCollaborateurRepository
{
    public function save(DTO\Collaborateur $dto): void;
}
