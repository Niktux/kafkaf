<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Controllers\Collaborateur;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.collaborateur'] = function() use($app) {
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
            ->match('/new', 'controller.collaborateur:formNewAction')
            ->method('GET')
            ->bind('collaborateur.new');

        $controllers
            ->match('/new', 'controller.collaborateur:newAction')
            ->method('POST')
            ->bind('collaborateur.post');

        return $controllers;
    }
}
