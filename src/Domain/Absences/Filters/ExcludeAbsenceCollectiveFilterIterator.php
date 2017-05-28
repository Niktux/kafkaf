<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\Filters;

use Niktux\Kafkaf\Domain\Absences\AbsenceCollective;
use Niktux\Kafkaf\Domain\Absences\JourFerie;

class ExcludeAbsenceCollectiveFilterIterator extends \FilterIterator implements \Countable
{
    private
        $keepJourFerie,
        $keepPartial;

    public function __construct(\Iterator $it, bool $keepPartial = true, bool $keepJourFerie = false)
    {
        parent::__construct($it);

        $this->keepPartial = $keepPartial;
        $this->keepJourFerie = $keepJourFerie;
    }

    public function accept()
    {
        $absence = $this->getInnerIterator()->current();

        if(! $absence instanceof AbsenceCollective)
        {
            return true;
        }

        if($this->keepPartial && $absence->isPartial())
        {
            return true;
        }

        if($this->keepJourFerie && $absence instanceof JourFerie)
        {
            return true;
        }

        return false;
    }

    public function count(): int
    {
        return iterator_count($this);
    }
}
