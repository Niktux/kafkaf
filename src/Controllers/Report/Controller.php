<?php

namespace Niktux\Kafkaf\Controllers\Report;

use Onyx\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Onyx\Services\CQS\QueryBuses\NullQueryBus;
use Onyx\Services\CQS\CommandBuses\NullCommandBus;
use Niktux\Kafkaf\Domain\CQS\Queries\CAF\CAFQuery;

class Controller
{
    use
        Traits\RequestAware,
        Traits\SessionAware,
        Traits\TwigAware,
        Traits\BusAware,
        Traits\UrlGeneratorAware,
        LoggerAwareTrait;

    public function __construct()
    {
        $this->logger = new NullLogger();
        $this->queryBus = new NullQueryBus();
        $this->commandBus = new NullCommandBus();
    }

    public function cafAction(int $from, int $to): Response
    {
        $query = new CAFQuery($from, $to);
        $result = $this->queryBus->send($query);

        return $this->renderResult('report/caf.twig', $result);
    }
}
