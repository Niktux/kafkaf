<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain;

use Puzzle\Pieces\ConvertibleToString;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;

class Collaborateur implements ConvertibleToString
{
    private
        $dto;

    public function __construct(DTO\Collaborateur $dto)
    {
        $this->dto = $dto;
    }

    public function uuid(): string
    {
        return $this->dto->uuid;
    }

    private function nom(): string
    {
        return $this->dto->nom;
    }

    private function prenom(): string
    {
        return $this->dto->prenom;
    }

    private function email(): string
    {
        return $this->dto->email;
    }

    public function __toString()
    {
        return sprintf(
            '%s %s',
            $this->prenom(),
            $this->nom()
       );
    }
}
