<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

use Niktux\Kafkaf\Domain\Collaborateur;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Persistence\CongeRepository;
use Niktux\Kafkaf\Services\WorkingDayAware;
use Niktux\Kafkaf\Domain\Absences\AbsenceProviders\JoursFeries;

class Conge implements Absence
{
    private const DATE_FORMAT = 'd-m-Y';

    use WorkingDayAware;

    private
        $dto;

    public function __construct(DTO\Conge $conge)
    {
        $this->dto = $conge;
    }

    private function uuid(): string
    {
        return $this->dto->uuid;
    }

    private function collaborateur(): Collaborateur
    {
        return $this->dto->load('collaborateur');
    }

    public function collaborateurUuid(): string
    {
        return $this->dto->collaborateurUuid;
    }

    public function from(): \DateTimeImmutable
    {
        return $this->dto->from;
    }

    public function to(): \DateTimeImmutable
    {
        return $this->dto->to;
    }

    public function duration(): int
    {
        $period = new \DatePeriod($this->from(), new \DateInterval('P1D'), $this->to()->modify('+1 day'));
        $days = 0;

        $joursFeries = new JoursFeries();

        foreach($period as $date)
        {
            if($this->isAWorkingDay($date) && ($joursFeries->isFerie($date) === false))
            {
                $days++;
            }
        }

        return $days;
    }

    public function description(): string
    {
        return "Congé";
    }

    public function persist(CongeRepository $repository)
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
