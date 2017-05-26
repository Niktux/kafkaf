<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\AllCollaborateurs;

use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryHandler;
use Niktux\Kafkaf\Persistence\CollaborateurRepository;
use Onyx\Services\CQS\QueryResult;

class Handler implements QueryHandler
{
    private
        $collaborateurRepository;

    public function __construct(CollaborateurRepository $repository)
    {
        $this->collaborateurRepository = $repository;
    }

    public function accept(Query $query): bool
    {
        return $query instanceof AllCollaborateursQuery;
    }

    public function handle(Query $query): QueryResult
    {
        $collaborateurs = $this->collaborateurRepository->findAll();

        return new Result($collaborateurs);
    }
}
