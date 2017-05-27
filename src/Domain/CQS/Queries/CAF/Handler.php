<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAF;

use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\QueryResult;
use Niktux\Kafkaf\Domain\Absences\AbsenceProviderCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Persistence\CollaborateurRepository;
use Niktux\Kafkaf\Domain\Absences\CollaborateurAbsenceFilterIterator;

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
        return $query instanceof CAFQuery;
    }

    public function handle(Query $query): QueryResult
    {
        $absences= new AbsenceCollection();

        foreach($this->providers as $provider)
        {
            $absences= $absences->mergeWith(
                $provider->list($query->from, $query->to)
            );
        }

        $collaborateurs = $this->collaborateurRepository->findAll();
        $result = new Result();

        foreach($collaborateurs as $collaborateur)
        {
            $result->add(
                $collaborateur,
                new CollaborateurAbsenceFilterIterator($absences, $collaborateur->uuid())
            );
        }

        return $result;
    }
}
