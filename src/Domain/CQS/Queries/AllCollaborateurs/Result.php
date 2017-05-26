<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\AllCollaborateurs;

use Onyx\Services\CQS\QueryResult;
use Niktux\Kafkaf\Domain\CollaborateurCollection;

/**
 * @immutable
 */
final class Result implements QueryResult
{
    private
        $collaborateurs;

    public function __construct(CollaborateurCollection $collaborateurs)
    {
        $this->collaborateurs = $collaborateurs;
    }

    public function getCollaborateurs(): CollaborateurCollection
    {
        return $this->collaborateurs;
    }
}
