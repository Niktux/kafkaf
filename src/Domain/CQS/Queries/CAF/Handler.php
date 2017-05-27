<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAF;

use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\QueryResult;
use Niktux\Kafkaf\Domain\Absences\AbsenceProviderCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;

class Handler implements QueryHandler
{
    private
        $providers;

    public function __construct(AbsenceProviderCollection $providers)
    {
        $this->providers = $providers;
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

        return new Result($absences);
    }
}
