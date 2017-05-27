<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class Provider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['repository.collaborateur'] = function ($c) {
            return new Repositories\Collaborateur($c['db']);
        };

        $container['repository.conge'] = function ($c) {
            return new Repositories\Conge($c['db'], $c['repository.collaborateur']);
        };
    }
}
