<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Commands\NewAbsence;

use Onyx\Services\CQS\Command;

class NewAbsenceCommand implements Command
{
    public
        $collaborateurUuid,
        $from,
        $to;
}
