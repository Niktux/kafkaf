<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence\DataTransferObjects;

use Onyx\Persistence\DataTransferObjects\Related;
use Onyx\Persistence\DataTransferObject;

class Absence extends Related implements DataTransferObject
{
    public
        $uuid,
        $collaborateurUuid,
        $from,
        $to;

    public function __construct()
    {
        parent::__construct(['collaborateur']);
    }
}
