<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\AbsenceProviders;

use Niktux\Kafkaf\Domain\Absences\AbsenceProvider;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Persistence\CongeRepository;

class Conges implements AbsenceProvider
{
    private
        $repository;

    public function __construct(CongeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list(int $weekFrom, int $weekTo): AbsenceCollection
    {
        // TODO FIXME at end of the year, working with 2 different years
        $year = (int) date('Y');

        $dti = new \DateTimeImmutable();
        $from = $dti->setISODate($year, $weekFrom, 1);
        $to = $dti->setISODate($year, $weekTo, 5);

        return $this->repository->findByPeriod($from, $to);
    }
}
