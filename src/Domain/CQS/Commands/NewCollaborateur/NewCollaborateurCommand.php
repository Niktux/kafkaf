<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Commands\NewCollaborateur;

use Onyx\Services\CQS\Command;

class NewCollaborateurCommand implements Command
{
    public
        $nom,
        $prenom,
        $email;
}
