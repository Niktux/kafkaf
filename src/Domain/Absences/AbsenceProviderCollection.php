<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

class AbsenceProviderCollection implements \IteratorAggregate, \Countable
{
    private
        $providers;

    public function __construct(array $providers = [])
    {
        foreach($providers as $provider)
        {
            if(! $provider instanceof AbsenceProvider)
            {
                throw new \InvalidArgumentException("Only AbsenceProvider are allowed in " . __CLASS__);
            }
        }

        $this->providers = $providers;
    }

    public function addProvider(AbsenceProvider $provider): self
    {
        $this->providers[] = $provider;

        return $this;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->providers);
    }

    public function mergeWith(self $collection): self
    {
        $allProviders = array_merge($this->providers, iterator_to_array($collection));

        return new self($allProviders);
    }

    public function count(): int
    {
        return count($this->providers);
    }
}
