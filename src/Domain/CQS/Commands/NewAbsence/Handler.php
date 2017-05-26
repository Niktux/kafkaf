<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Commands\NewAbsence;

use Niktux\Kafkaf\Persistence\CollaborateurRepository;
use Onyx\Services\CQS\CommandHandler;
use Niktux\Kafkaf\Persistence\AbsenceRepository;
use Onyx\Services\CQS\Command;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Domain\Collaborateur;
use Niktux\Kafkaf\Domain\Absences as Domain;
use Ramsey\Uuid\Uuid;

class Handler implements CommandHandler
{
    private
        $absenceRepository,
        $collaborateurRepository;

    public function __construct(AbsenceRepository $absenceRepository, CollaborateurRepository $collaborateurRepository)
    {
        $this->absenceRepository = $absenceRepository;
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

        $dto = new DTO\Absence();
        $dto->uuid = Uuid::uuid4()->toString();
        $dto->collaborateurUuid = $command->collaborateurUuid;
        $dto->from = $command->from;
        $dto->to = $command->to;

        $absence = new Domain\Absence($dto);
        $absence->persist($this->absenceRepository);
    }
}
