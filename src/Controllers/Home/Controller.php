<?php

namespace Niktux\Kafkaf\Controllers\Home;

use Onyx\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Niktux\Kafkaf\Domain\CQS\Queries\Dashboard\DashboardQuery;
use Onyx\Services\CQS\QueryBuses\NullQueryBus;

class Controller
{
    use
        Traits\RequestAware,
        Traits\TwigAware,
        Traits\QueryBusAware,
        LoggerAwareTrait;

    public function __construct()
    {
        $this->logger = new NullLogger();
        $this->queryBus = new NullQueryBus();
    }

    public function homeAction(): Response
    {
        $query = new DashboardQuery();

        $result = $this->queryBus->send($query);

        return $this->renderResult('home.twig', $result);
    }
}
