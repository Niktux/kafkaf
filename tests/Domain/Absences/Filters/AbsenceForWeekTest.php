<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\Filters;

use PHPUnit\Framework\TestCase;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollective;

class AbsenceForWeekTest extends TestCase
{
    public function testFilter()
    {
        $from = new \DateTimeImmutable('2017-05-15');
        $to = new \DateTimeImmutable('2017-06-04');

        $collection = new AbsenceCollection();
        $collection->addAbsence(new AbsenceCollective($from, $to, 'congé'));

        $filtered = AbsenceForWeek::filter($collection, 21);

        $this->assertSame(5, $filtered->totalDuration());
    }
}
