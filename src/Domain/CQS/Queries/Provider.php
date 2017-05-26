<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

class Provider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['query.handlers.dashboard_dashboardquery'] = function($c) {
            return new Dashboard\Handler($c['repository.absence']);
        };

        $container['query.handlers.allcollaborateurs_allcollaborateursquery'] = function($c) {
            return new AllCollaborateurs\Handler($c['repository.collaborateur']);
        };
    }
}