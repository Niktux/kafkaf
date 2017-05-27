<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

interface JoursFeriesProvider
{
    public function isFerie(\DateTimeImmutable $day): bool;
}
