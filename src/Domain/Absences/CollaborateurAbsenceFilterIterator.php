<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

class CollaborateurAbsenceFilterIterator extends \FilterIterator
{
    private
        $collaborateurUuid;

    public function __construct(AbsenceCollection $collection, string $collaborateurUuid)
    {
        parent::__construct($collection->getIterator());

        $this->collaborateurUuid = $collaborateurUuid;
    }

    public function accept(): bool
    {
        $absence = $this->getInnerIterator()->current();

        if($absence instanceof AbsenceCollective)
        {
            return true;
        }

        if($absence instanceof Conge)
        {
            return $absence->collaborateurUuid() === $this->collaborateurUuid;
        }

        return false;
    }
}
