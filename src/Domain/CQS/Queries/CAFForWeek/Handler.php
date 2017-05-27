<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAFForWeek;

use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\QueryResult;
use Niktux\Kafkaf\Domain\Absences\AbsenceProviderCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Persistence\CollaborateurRepository;
use Niktux\Kafkaf\Domain\Absences\Filters\CollaborateurAbsenceFilterIterator;
use Niktux\Kafkaf\Domain\Absences\Filters\AbsenceForWeek;
use Niktux\Kafkaf\Domain\Absences\Filters\AbsenceDeduplicate;

class Handler implements QueryHandler
{
    private
        $providers,
        $collaborateurRepository;

    public function __construct(AbsenceProviderCollection $providers, CollaborateurRepository $repository)
    {
        $this->providers = $providers;
        $this->collaborateurRepository = $repository;
    }

    public function accept(Query $query): bool
    {
        return $query instanceof CAFForWeekQuery;
    }

    public function handle(Query $query): QueryResult
    {
        $absences = new AbsenceCollection();

        foreach($this->providers as $provider)
        {
            $absences = $absences->mergeWith(
                $provider->list(1, 52)
            );
        }

        $collaborateurs = $this->collaborateurRepository->findAll();
        $result = new Result();

        foreach($collaborateurs as $collaborateur)
        {
            $weekAbsences = AbsenceForWeek::filter($absences, $query->week);
            $weekAbsences = new CollaborateurAbsenceFilterIterator($weekAbsences, $collaborateur->uuid());
            $weekAbsences = AbsenceDeduplicate::filter($weekAbsences);

            $result->add(
                $collaborateur,
                $weekAbsences
            );
        }

        $year = (int) date('Y');
        $dti = new \DateTimeImmutable();
        $result->week = $query->week;
        $result->dates = sprintf(
            "%s au %s",
            $dti->setISODate($year, $query->week, 1)->format('d-m-Y'),
            $dti->setISODate($year, $query->week, 5)->format('d-m-Y')
        );

        return $result;
    }
}
