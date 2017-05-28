<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\Filters;

use PHPUnit\Framework\TestCase;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollective;
use Niktux\Kafkaf\Domain\Absences\Conge;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Domain\Absences\JourFerie;

class AbsenceDeduplicateTest extends TestCase
{
    public function testFilter()
    {
        $collection = new AbsenceCollection();

        $collection->addAbsence($this->cg('06-20', '06-22')); // 3 days
        $collection->addAbsence($this->ac('06-22','06-23')); // 2 days but 1 overlapped

        $filteredCollection = AbsenceDeduplicate::filter($collection->getIterator());

        $this->assertCount(2, $filteredCollection);
        $this->assertSame(4, $filteredCollection->totalDuration());

        $nbPartial = 0;
        foreach($filteredCollection as $absence)
        {
            if($absence->isPartial())
            {
                $nbPartial++;
            }
        }

        $this->assertSame(1, $nbPartial);

        $collection->addAbsence($this->cg('05-08', '05-10')); // 3 days (but duration = 2 becauseof day off)
        $collection->addAbsence($this->jf('05-08', '05-08')); // 1 dayoff overlapped

        $filteredCollection = AbsenceDeduplicate::filter($collection->getIterator());

        $this->assertCount(4, $filteredCollection); // dayoff must be kept
        $this->assertSame(7, $filteredCollection->totalDuration());
    }

    private function ac(string $from, string $to)
    {
        $from = new \DateTimeImmutable("2017-$from");
        $to = new \DateTimeImmutable("2017-$to");

        return new AbsenceCollective($from, $to, "Collective");
    }

    private function jf(string $from, string $to)
    {
        $from = new \DateTimeImmutable("2017-$from");
        $to = new \DateTimeImmutable("2017-$to");

        return new JourFerie($from, $to, "Férié");
    }

    private function cg(string $from, string $to)
    {
        $from = new \DateTimeImmutable("2017-$from");
        $to = new \DateTimeImmutable("2017-$to");

        $dto = new DTO\Conge();
        $dto->from = $from;
        $dto->to = $to;

        return new Conge($dto);
    }
}
