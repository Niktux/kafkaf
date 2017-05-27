<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

class AbsenceCollection implements \IteratorAggregate, \Countable
{
    private
        $absences;

    public function __construct(array $absences = [])
    {
        foreach($absences as $absence)
        {
            if(! $absence instanceof Absence)
            {
                throw new \InvalidArgumentException("Only Absence are allowed in " . __CLASS__);
            }
        }

        $this->absences = $absences;
    }

    public function addAbsence(Absence $absence): self
    {
        $this->absences[] = $absence;

        return $this;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->absences);
    }

    public function mergeWith(self $collection): self
    {
        $allProviders = array_merge($this->absences, iterator_to_array($collection));

        return new self($allProviders);
    }

    public function count(): int
    {
        return count($this->absences);
    }

    public function totalDuration(): int
    {
        $duration = 0;

        foreach($this->absences as $absence)
        {
            $duration += $absence->duration();
        }

        return $duration;
    }
}
