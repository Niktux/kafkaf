<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

use Puzzle\Pieces\ConvertibleToString;
use Niktux\Kafkaf\Domain\Collaborateur;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Persistence\AbsenceRepository;

class Absence implements ConvertibleToString
{
    private const DATE_FORMAT = 'd-m-Y';

    private
        $dto;

    public function __construct(DTO\Absence $absence)
    {
        $this->dto = $absence;
    }

    private function uuid(): string
    {
        return $this->dto->uuid;
    }

    private function collaborateur(): Collaborateur
    {
        return $this->dto->load('collaborateur');
    }

    private function from(): \DateTimeInterface
    {
        return $this->dto->from;
    }

    private function to(): \DateTimeInterface
    {
        return $this->dto->to;
    }

    public function duration(): int
    {
        $interval = $this->from()->diff($this->to());

        // FIXME jours feries & week-end à enlever

        return $interval->d;
    }

    public function persist(AbsenceRepository $repository)
    {
        $repository->save($this->dto);
    }

    public function __toString()
    {
        return sprintf(
            "%s absent du %s au %s (%.1f jours)",
            $this->collaborateur(),
            $this->from()->format(self::DATE_FORMAT),
            $this->to()->format(self::DATE_FORMAT),
            $this->duration()
        );
    }
}
