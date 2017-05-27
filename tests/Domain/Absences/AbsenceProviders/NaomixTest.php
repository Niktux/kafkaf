<?php

namespace Niktux\Kafkaf\Domain\Absences\AbsenceProviders;

use PHPUnit\Framework\TestCase;

class NaomixTest extends TestCase
{
    /**
     * @dataProvider providerTestList
     */
    public function testList(int $sDebut, int $sFin, int $expected, int $expectedDurationInDays)
    {
        $naomix = new Naomix(2017);

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
}
