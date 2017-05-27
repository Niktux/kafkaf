<?php

namespace Niktux\Kafkaf;

use Silex\Provider\SessionServiceProvider;
use Onyx\Providers;
use Onyx\Services\CQS\QueryBuses;
use Onyx\Services\CQS\CommandBuses;
use Onyx\Services\CQS\QueryHandlerProviders;
use Onyx\Services\CQS\CommandHandlerProviders;

class Application extends \Onyx\Application
{
    protected function registerProviders(): void
    {
        $this->register(new SessionServiceProvider());
        $this->register(new Providers\Monolog([
            // insert your loggers here
        ]));
        $this->register(new Providers\Twig());
        $this->register(new Providers\Webpack());
        $this->register(new Providers\DBAL());

        $this->register(new Domain\Provider());
        $this->register(new Persistence\Provider());

        $this->register(new Domain\CQS\Queries\Provider());
        $this->register(new Domain\CQS\Commands\Provider());

    }

    protected function initializeServices(): void
    {
        $this->configureTwig();

        $this['queryBus'] = function ($c) {
            return new QueryBuses\Synchronous($this['queryHandlerProvider']);
        };

        $this['queryHandlerProvider'] = function ($c) {
            return new QueryHandlerProviders\Pimple($this, 'Domain\CQS\Queries');
        };

        $this['commandBus'] = function ($c) {
            return new CommandBuses\Synchronous($this['commandHandlerProvider']);
        };

        $this['commandHandlerProvider'] = function ($c) {
            return new CommandHandlerProviders\Pimple($this, 'Domain\CQS\Commands');
        };
    }

    private function configureTwig(): void
    {
        $this['view.manager']->addPath(array(
            $this['root.path'] . 'views/',
        ));
    }

    protected function mountControllerProviders(): void
    {
        $this->mount('/', new Controllers\Home\Provider());
        $this->mount('/absences', new Controllers\Absence\Provider());
        $this->mount('/report', new Controllers\Report\Provider());
    }
}
