<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

class NullAbsence implements Absence
{
    public function from(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('U', 0);
    }

    public function to(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('U', 0);
    }

    public function duration(): int
    {
        return 0;
    }

    public function description(): string
    {
        return "Pas d'absence";
    }

    public function restrictToWeek(int $week): Absence
    {
        return $this;
    }

    public function __toString(): string
    {
        return "Ceci n'est pas une absence (si si j'insiste !)";
    }

    public function isPartial(): bool
    {
        return false;
    }

    public function declareAsPartial(): self
    {
        throw new \LogicException(__CLASS__ . " cannot be partial");
    }
}
