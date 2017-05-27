<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence;

use Niktux\Kafkaf\Domain\Collaborateur;
use Niktux\Kafkaf\Domain\CollaborateurCollection;

interface ReadableCollaborateurRepository
{
    public function find(string $uuid): ?Collaborateur;
    public function findAll(): CollaborateurCollection;
}
