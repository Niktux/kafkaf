<?php

namespace Niktux\Kafkaf\Domain\Absences\AbsenceProviders;

use PHPUnit\Framework\TestCase;
use Niktux\Kafkaf\Domain\Absences\JoursFeriesProvider;

class NaomixTest extends TestCase
{
    /**
     * @dataProvider providerTestList
     */
    public function testList(int $sDebut, int $sFin, int $expected, int $expectedDurationInDays)
    {
        $naomix = new Naomix(new JoursFeries(), 2017);

        $collection = $naomix->list($sDebut, $sFin);

        $this->assertCount($expected, $collection);
        $this->assertSame($expectedDurationInDays, $collection->totalDuration());
    }

    public function providerTestList()
    {
        return [
            'semaine de naomix' => [1, 1, 1, 2],
            'semaine de naomix en mai ' => [21, 21, 1, 2],
            'semaine sans naomix' => [2, 2, 0, 0],
            'semaine sans naomix en mai' => [20, 20, 0, 0],
            '3 semaines sans naomix' => [2, 4, 0, 0],
            'mois avec 1 naomix' => [4, 7, 1, 2],
            'période avec 2 naomix' => [4, 9, 2, 4],
            'bts' => [37, 37, 2, 3],
            '2 naomix + bts' => [30, 40, 3, 5],
        ];
    }

    /**
     * @dataProvider providerTestListWithJourFerie
     */
    public function testListWithJourFerie(JoursFeriesProvider $provider, int $expectedFrom, int $expectedTo)
    {
        $naomix = new Naomix($provider, 2017);

        $collection = $naomix->list(1, 1);

        $this->assertCount(1, $collection);
        $absence = iterator_to_array($collection)[0];

        $this->assertSame($expectedFrom, (int) $absence->from()->format('w'));
        $this->assertSame($expectedTo, (int) $absence->to()->format('w'));
    }

    public function providerTestListWithJourFerie()
    {
        $provider = function($dayInWeek) {
            return new class($dayInWeek) implements JoursFeriesProvider {
                private $day;
                public function __construct(int $dayInWeek) { $this->day = $dayInWeek; }
                public function isFerie(\DateTimeImmutable $day): bool { return intval($day->format('w')) === $this->day; }
            };
        };

        return [
            'jeudi férié' => [$provider(4) , 2 /* mardi */, 3 /* mercredi */],
            'vendredi férié' => [$provider(5) , 3 /* mercredi */, 4 /* jeudi */],
            'mercredi férié' => [$provider(3) , 4 /* jeudi */, 5 /* vendredi */],
            'dimanche férié' => [$provider(0), 4 /* jeudi */, 5 /* vendredi */],
            'aucun jour férié' => [$provider(100), 4 /* jeudi */, 5 /* vendredi */],
        ];
    }
}

