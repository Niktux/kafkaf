<?php

namespace Niktux\Kafkaf\Controllers\Report;

use Onyx\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Onyx\Services\CQS\QueryBuses\NullQueryBus;
use Onyx\Services\CQS\CommandBuses\NullCommandBus;
use Niktux\Kafkaf\Domain\CQS\Queries\CAF\CAFQuery;
use Niktux\Kafkaf\Domain\CQS\Queries\CAFForWeek\CAFForWeekQuery;

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
        $result = $this->queryBus->send(new CAFQuery($from, $to));

        return $this->renderResult('report/caf.twig', $result);
    }

    public function cafFroWeekAction(int $week): Response
    {
        $result = $this->queryBus->send(new CAFForWeekQuery($week));

        return $this->renderResult('report/cafForWeek.twig', $result);
    }
}
