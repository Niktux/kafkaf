<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\Filters;

use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Domain\Absences\NullAbsence;

class AbsenceDeduplicate
{
    public static function filter(\Iterator $iterator): AbsenceCollection
    {
        $filteredCollection = new AbsenceCollection();

        $conges = new CongeFilterIterator($iterator);
        $collectives = new AbsenceCollectiveFilterIterator($iterator, true);

        foreach($collectives as $collective)
        {
            foreach($conges as $conge)
            {
                $collective = $collective->excludeFrom($conge);
            }

            if(! $collective instanceof NullAbsence)
            {
                $filteredCollection->addAbsence($collective);
            }
        }

        foreach($conges as $conge)
        {
            $filteredCollection->addAbsence($conge);
        }

        foreach(new JourFerieFilterIterator($iterator) as $jourFerie)
        {
            $filteredCollection->addAbsence($jourFerie);
        }

        return $filteredCollection;
    }
}
