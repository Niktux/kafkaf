<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence\Repositories;

use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Domain as Domain;
use Doctrine\DBAL\Driver\Connection;
use Onyx\Persistence\DTOHydrators\ByField;
use Onyx\Persistence\Fields;
use Niktux\Kafkaf\Persistence\CollaborateurRepository;
use Niktux\Kafkaf\Domain\CollaborateurCollection;

class Collaborateur implements CollaborateurRepository
{
    private const TABLE_NAME = 'collaborateurs';

    private
        $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function find(string $uuid): ?Domain\Collaborateur
    {
        $statement = $this->db->executeQuery(
            sprintf('SELECT * FROM %s WHERE uuid = ?', self::TABLE_NAME),
            [$uuid]
        );
        $result = $statement->fetch();

        if($result === false)
        {
            return null;
        }

        return $this->buildDomainObject($result);
    }

    public function findAll(): CollaborateurCollection
    {
        $collection = new CollaborateurCollection();

        $statement = $this->db->executeQuery(sprintf('SELECT * FROM %s ORDER BY nom ASC', self::TABLE_NAME));
        $result = $statement->fetchAll();

        foreach($result as $row)
        {
            $collection->addCollaborateur($this->buildDomainObject($row));
        }

        return $collection;
    }

    public function save(DTO\Collaborateur $dto): void
    {
        // FIXME TODO handle unique violation errors

        $this->db->insert(
            self::TABLE_NAME,
            [
                'uuid' => $dto->uuid,
                'nom' => $dto->nom,
                'prenom' => $dto->prenom,
                'email' => $dto->email,
            ],
            [
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
            ]
        );
    }

    private function buildDomainObject(array $row): Domain\Collaborateur
    {
        $dto = $this->buildDTOObject($row);

        return new Domain\Collaborateur($dto);
    }

    private function buildDTOObject(array $row): DTO\Collaborateur
    {
        $fields = [
            'uuid' => new Fields\NotNullable(new Fields\StringField('uuid')),
            'nom' => new Fields\NotNullable(new Fields\StringField('nom')),
            'prenom' => new Fields\NotNullable(new Fields\StringField('prenom')),
            'email' => new Fields\NotNullable(new Fields\StringField('email')),
        ];

        $hydrator = new ByField($fields);
        $dto = $hydrator->hydrate(new DTO\Collaborateur(), $row);

        return $dto;
    }
}
