<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Commands\NewCollaborateur;

use Niktux\Kafkaf\Persistence\CollaborateurRepository;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Domain as Domain;
use Ramsey\Uuid\Uuid;

class Handler implements CommandHandler
{
    private
        $collaborateurRepository;

    public function __construct(CollaborateurRepository $collaborateurRepository)
    {
        $this->collaborateurRepository = $collaborateurRepository;
    }

    public function accept(Command $command): bool
    {
        return $command instanceof NewCollaborateurCommand;
    }

    public function handle(Command $command): void
    {
        $dto = new DTO\Collaborateur();
        $dto->uuid = Uuid::uuid4()->toString();
        $dto->nom = $command->nom;
        $dto->prenom = $command->prenom;
        $dto->email = $command->email;

        $collaborateur = new Domain\Collaborateur($dto);
        $collaborateur->persist($this->collaborateurRepository);
    }
}
