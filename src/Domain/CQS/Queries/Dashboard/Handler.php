<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\Dashboard;

use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\QueryHandler;
use Niktux\Kafkaf\Persistence\CongeRepository;

class Handler implements QueryHandler
{
    private
        $congeRepository;

    public function __construct(CongeRepository $repository)
    {
        $this->congeRepository = $repository;
    }

    public function accept(Query $query): bool
    {
        return $query instanceof DashboardQuery;
    }

    public function handle(Query $query): QueryResult
    {
        $conge = $this->congeRepository->find('a006aae7-b17d-4bfc-afb2-923e84ee631f');

        $result = new class implements QueryResult {};
        $result->conge = $conge;

        return $result;
    }
}
