<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Commands\NewAbsence;

use Niktux\Kafkaf\Persistence\CollaborateurRepository;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Domain\Collaborateur;
use Niktux\Kafkaf\Domain\Absences as Domain;
use Ramsey\Uuid\Uuid;
use Niktux\Kafkaf\Persistence\CongeRepository;

class Handler implements CommandHandler
{
    private
        $congeRepository,
        $collaborateurRepository;

    public function __construct(CongeRepository $congeRepository, CollaborateurRepository $collaborateurRepository)
    {
        $this->congeRepository = $congeRepository;
        $this->collaborateurRepository = $collaborateurRepository;
    }

    public function accept(Command $command): bool
    {
        return $command instanceof NewAbsenceCommand;
    }

    public function handle(Command $command): void
    {
        $collaborateur = $this->collaborateurRepository->find($command->collaborateurUuid);

        if(! $collaborateur instanceof Collaborateur)
        {
            throw new \InvalidArgumentException("Collaborateur $command->collaborateurUuid does not exist");
        }

        $dto = new DTO\Conge();
        $dto->uuid = Uuid::uuid4()->toString();
        $dto->collaborateurUuid = $command->collaborateurUuid;
        $dto->from = $command->from;
        $dto->to = $command->to;

        $conge = new Domain\Conge($dto);
        $conge->persist($this->congeRepository);
    }
}
