<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Controllers\Collaborateur;

use Onyx\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Onyx\Services\CQS\CommandBuses\NullCommandBus;
use Onyx\Services\CQS\QueryBuses\NullQueryBus;
use Niktux\Kafkaf\Domain\CQS\Commands\NewCollaborateur\NewCollaborateurCommand;

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
        return $this->render('collaborateur/new.twig');
    }

    public function newAction(): Response
    {
        // TODO FIXME inputs validation

        $postParameters = $this->request->request->all();

        $command = new NewCollaborateurCommand();

        $command->nom = $postParameters['nom'];
        $command->prenom = $postParameters['prenom'];
        $command->email = $postParameters['email'];

        $this->commandBus->send($command);

        $this->addSuccessFlash('Collaborateur créé');

        return $this->redirect('home');
    }
}
