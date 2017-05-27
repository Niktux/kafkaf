<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Persistence\DataTransferObjects;

use Onyx\Persistence\DataTransferObject;

class Collaborateur implements DataTransferObject
{
    public
        $uuid,
        $nom,
        $prenom,
        $email;

    public static function construct(string $uuid, string $nom, string $prenom, string $email): self
    {
        $dto = new self();
        $dto->uuid = $uuid;
        $dto->nom = $nom;
        $dto->prenom = $prenom;
        $dto->email = $email;

        return $dto;
    }
}
