<?php

namespace Niktux\Kafkaf\Controllers\Absence;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.absence'] = function() use($app) {
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
            ->match('/new', 'controller.absence:formNewAction')
            ->method('GET')
            ->bind('absence.new');

        $controllers
            ->match('/new', 'controller.absence:newAction')
            ->method('POST')
            ->bind('absence.post');

        return $controllers;
    }
}
