<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence\Repositories;

use Niktux\Kafkaf\Persistence\AbsenceRepository;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Domain\Absences as Domain;
use Doctrine\DBAL\Driver\Connection;
use Onyx\Persistence\DTOHydrators\ByField;
use Onyx\Persistence\Fields;
use Niktux\Kafkaf\Persistence\CollaborateurRepository;

class Absence implements AbsenceRepository
{
    private const TABLE_NAME = 'absences';

    private
        $db,
        $collaborateurRepository;

    public function __construct(Connection $db, CollaborateurRepository $collaborateurRepository)
    {
        $this->db = $db;
        $this->collaborateurRepository = $collaborateurRepository;
    }

    public function find(string $uuid): ?Domain\Absence
    {
        $statement = $this->db->executeQuery(
            sprintf("SELECT * FROM %s WHERE uuid = ?", self::TABLE_NAME),
            [$uuid]
        );

        $row= $statement->fetch();

        if($row=== false)
        {
            return null;
        }

        return $this->buildDomainObject($row);
    }

    public function save(DTO\Absence $dto): void
    {
        // FIXME TODO handle unique violation errors

        $this->db->insert(
            self::TABLE_NAME,
            [
                'uuid' => $dto->uuid,
                'collaborateur' => $dto->collaborateurUuid,
                'dateFrom' => $dto->from->format('Y-m-d H:i:s'),
                'dateTo' => $dto->to->format('Y-m-d H:i:s'),
            ],
            [
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
            ]
        );
    }

    private function buildDomainObject(array $row): Domain\Absence
    {
        $dto = $this->buildDTOObject($row);

        $dto->set('collaborateur', function() use($dto) {
            return $this->collaborateurRepository->find($dto->collaborateurUuid);
        });

        return new Domain\Absence($dto);
    }

    private function buildDTOObject(array $row): DTO\Absence
    {
        $fields = [
            'uuid' => new Fields\NotNullable(new Fields\StringField('uuid')),
            'collaborateurUuid' => new Fields\NotNullable(new Fields\StringField('collaborateur')),
            'from' => new Fields\NotNullable(new Fields\DateTime('dateFrom', 'Y-m-d H:i:s')),
            'to' => new Fields\NotNullable(new Fields\DateTime('dateTo', 'Y-m-d H:i:s')),
        ];

        $hydrator = new ByField($fields);
        $dto = $hydrator->hydrate(new DTO\Absence(), $row);

        return $dto;
    }
}
