<?php

namespace Niktux\Kafkaf\Controllers\Report;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.report'] = function() use($app) {
            $controller = new Controller();
            $controller
                ->setRequest($app['request_stack'])
                ->setSession($app['session'])
                ->setTwig($app['twig'])
                ->setUrlGenerator($app['url_generator'])
                ->setBuses($app['queryBus'], $app['commandBus']);

            return $controller;
        };

        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/caf/from/{from}/to/{to}', 'controller.report:cafAction')
            ->method('GET')
            ->assert('from', '\d+')
            ->assert('to', '\d+')
            ->bind('report.caf');

        $controllers
            ->match('/caf/week/{week}', 'controller.report:cafFroWeekAction')
            ->method('GET')
            ->assert('week', '\d+')
            ->bind('report.cafForWeek');

        return $controllers;
    }
}
