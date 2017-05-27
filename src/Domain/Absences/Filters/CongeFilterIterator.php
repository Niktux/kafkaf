<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\Filters;

use Niktux\Kafkaf\Domain\Absences\Conge;

class CongeFilterIterator extends \FilterIterator
{
    public function accept()
    {
        return $this->getInnerIterator()->current() instanceof Conge;
    }
}
