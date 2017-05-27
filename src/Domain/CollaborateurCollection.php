<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain;

class CollaborateurCollection implements \IteratorAggregate, \Countable
{
    private
        $collaborateurs;

    public function __construct()
    {
        $this->collaborateurs = [];
    }

    public function addCollaborateur(Collaborateur $collaborateur): self
    {
        $this->collaborateurs[] = $collaborateur;

        return $this;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->collaborateurs);
    }

    public function count(): int
    {
        return count($this->collaborateurs);
    }
}
