<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Niktux\Kafkaf\Domain\Absences\AbsenceProviderCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceProviders\Naomix;
use Niktux\Kafkaf\Domain\Absences\AbsenceProviders\JoursFeries;
use Niktux\Kafkaf\Domain\Absences\AbsenceProviders\Conges;

class Provider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['congeProvider'] = function ($c) {
            return new Conges($c['repository.conge']);
        };

        $container['absenceProviders'] = function ($c) {
            return new AbsenceProviderCollection([
                new Naomix(),
                new JoursFeries(),
                $c['congeProvider'],
            ]);
        };
    }
}
