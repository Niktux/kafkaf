<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences\AbsenceProviders;

use PHPUnit\Framework\TestCase;

class JoursFeriesTest extends TestCase
{
    /**
     * @dataProvider providerTestList
     */
    public function testList(int $sDebut, int $sFin, int $expected, int $year)
    {
        $jf = new JoursFeries($year);

        $collection = $jf->list($sDebut, $sFin);

        $this->assertCount($expected, $collection);
        $this->assertSame($expected, $collection->totalDuration());
    }

    public function providerTestList()
    {
        return [
            "Jour de l'an" => [52, 52, 0, 2016], // weekend
            'Pâques' => [16, 16, 1, 2017],
            '1er mai' => [18, 18, 1, 2017],
            '8 mai ' => [19, 19, 1, 2017],
            'Ascension' => [21, 21, 1, 2017],
            'Pentecôte' => [23, 23, 1, 2017],
            'Fête nationale' => [28, 28, 1, 2017],
            '15 août' => [33, 33, 1, 2017],
            'Toussaint' => [44, 44, 1, 2017],
            '11 Novembre' => [45, 45, 0, 2017], // weekend
            'Noël' => [52, 52, 1, 2017],
            'all 2017' => [1, 52, 9, 2017], // 11 - 2 weekend
        ];
    }
}
