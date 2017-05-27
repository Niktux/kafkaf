<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAF;

use Onyx\Services\CQS\Query;

class CAFQuery implements Query
{
    public
        $from,
        $to;

    public function __construct(int $from, int $to)
    {
        $this->from = $from;
        $this->to = $to;
    }
}
