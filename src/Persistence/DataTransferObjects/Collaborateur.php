<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence\DataTransferObjects;

use Onyx\Persistence\DataTransferObject;

class Collaborateur implements DataTransferObject
{
    public
        $uuid,
        $nom,
        $prenom,
        $email;
}
