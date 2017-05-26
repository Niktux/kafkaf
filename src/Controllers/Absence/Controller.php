<?php

namespace Niktux\Kafkaf\Controllers\Absence;

use Onyx\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Onyx\Services\CQS\QueryBuses\NullQueryBus;
use Onyx\Services\CQS\CommandBuses\NullCommandBus;
use Niktux\Kafkaf\Domain\CQS\Queries\AllCollaborateurs\AllCollaborateursQuery;
use Niktux\Kafkaf\Domain\CQS\Commands\NewAbsence\NewAbsenceCommand;

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

    public function formNewAction(): Response
    {
        $query = new AllCollaborateursQuery();
        $result = $this->queryBus->send($query);

        return $this->renderResult('absence/new.twig', $result);
    }

    public function newAction(): Response
    {
        // TODO FIXME inputs validation

        $postParameters = $this->request->request->all();

        $command = new NewAbsenceCommand();

        $command->collaborateurUuid = $postParameters['collaborateur'];
        $command->from = \DateTimeImmutable::createFromFormat('Y-m-d', $postParameters['from']);
        $command->to = \DateTimeImmutable::createFromFormat('Y-m-d', $postParameters['to']);

        $this->commandBus->send($command);

        return $this->redirect('home');
    }
}
