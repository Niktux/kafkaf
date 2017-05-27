<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Commands;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

class Provider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['command.handlers.newabsence_newabsencecommand'] = function($c) {
            return new NewAbsence\Handler($c['repository.conge'], $c['repository.collaborateur']);
        };

        $container['command.handlers.newcollaborateur_newcollaborateurcommand'] = function($c) {
            return new NewCollaborateur\Handler($c['repository.collaborateur']);
        };
    }
}
