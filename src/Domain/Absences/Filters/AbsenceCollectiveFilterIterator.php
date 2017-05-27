<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\Filters;

use Niktux\Kafkaf\Domain\Absences\AbsenceCollective;
use Niktux\Kafkaf\Domain\Absences\JourFerie;

class AbsenceCollectiveFilterIterator extends \FilterIterator
{
    private
        $excludeJourFerie;

    public function __construct(\Iterator $it, bool $excludeJourFerie = true)
    {
        parent::__construct($it);

        $this->excludeJourFerie = $excludeJourFerie;
    }

    public function accept()
    {
        $absence = $this->getInnerIterator()->current();

        if(! $absence instanceof AbsenceCollective)
        {
            return false;
        }

        if($this->excludeJourFerie)
        {
            return ! $absence instanceof JourFerie;
        }

        return true;
    }
}
