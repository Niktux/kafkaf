<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAFForWeek;

use Onyx\Services\CQS\Query;

class CAFForWeekQuery implements Query
{
    public
        $week;

    public function __construct(int $week)
    {
        $this->week = $week;
    }
}
