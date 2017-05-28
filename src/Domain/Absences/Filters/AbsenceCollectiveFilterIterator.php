<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\Filters;

use Niktux\Kafkaf\Domain\Absences\AbsenceCollective;
use Niktux\Kafkaf\Domain\Absences\JourFerie;

class AbsenceCollectiveFilterIterator extends \FilterIterator implements \Countable
{
    private
        $keepJourFerie;

        public function __construct(\Iterator $it, bool $keepJourFerie = false)
    {
        parent::__construct($it);

        $this->keepJourFerie= $keepJourFerie;
    }

    public function accept()
    {
        $absence = $this->getInnerIterator()->current();

        if(! $absence instanceof AbsenceCollective)
        {
            return false;
        }

        if($this->keepJourFerie === false)
        {
            return ! $absence instanceof JourFerie;
        }

        return true;
    }

    public function count(): int
    {
        return iterator_count($this);
    }
}
