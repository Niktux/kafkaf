<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

use Puzzle\Pieces\ConvertibleToString;

interface Absence extends ConvertibleToString
{
    public function from(): \DateTimeImmutable;
    public function to(): \DateTimeImmutable;

    public function duration(): int;
    public function description(): string;
}
