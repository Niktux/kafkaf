<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\Absences;

use PHPUnit\Framework\TestCase;

class AbsenceCollectiveTest extends TestCase
{
    public function testRestrictToWeekForYear()
    {
        $this->markTestSkipped("Semaines dans des années différentes pas encore gérées");

        $from = new \DateTimeImmutable('2017-01-01');
        $to = new \DateTimeImmutable('2017-12-31');

        $absence = new AbsenceCollective($from, $to, 'Pony');

        $this->assertSame(
            5,
            $absence->restrictToWeek(21)->duration()
        );
    }

    /**
     * @dataProvider providerTestRestrictToWeek
     */
    public function testRestrictToWeek(string $from, string $to, int $week, int $expectedDuration)
    {
        $from = new \DateTimeImmutable($from);
        $to = new \DateTimeImmutable($to);

        $absence = new AbsenceCollective($from, $to, 'Pony');

        $this->assertSame(
            $expectedDuration,
            $absence->restrictToWeek($week)->duration()
        );
    }

    public function providerTestRestrictToWeek()
    {
        return [
            ["2017-05-22", "2017-05-26", 20, 0],
            ["2017-05-22", "2017-05-26", 21, 5],
            ["2017-05-22", "2017-05-26", 22, 0],

            ["2017-05-12", "2017-05-26", 21, 5],
            ["2017-05-22", "2017-05-31", 21, 5],
            ["2017-05-12", "2017-05-31", 21, 5],

            ["2017-05-15", "2017-06-04", 21, 5],
            ["2017-03-01", "2017-07-31", 21, 5],

            ["2017-05-24", "2017-05-26", 20, 0],
            ["2017-05-24", "2017-05-26", 21, 3],
            ["2017-05-24", "2017-05-29", 20, 0],
            ["2017-05-24", "2017-05-29", 21, 3],
            ["2017-05-24", "2017-05-29", 22, 1],
            ["2017-05-24", "2017-05-31", 21, 3],
            ["2017-05-24", "2017-05-31", 22, 3],

            ["2017-05-22", "2017-05-23", 20, 0],
            ["2017-05-22", "2017-05-23", 21, 2],
            ["2017-05-22", "2017-05-23", 22, 0],
            ["2017-05-16", "2017-05-23", 20, 4],
            ["2017-05-16", "2017-05-23", 21, 2],
            ["2017-05-01", "2017-05-23", 20, 5],
            ["2017-05-01", "2017-05-23", 21, 2],
        ];
    }

    /**
     * @dataProvider providerTestExcludeFrom
     */
    public function testExcludeFrom(string $from, string $to, ?string $expectedFrom, ?string $expectedTo = null)
    {
        $absence = new AbsenceCollective(new \DateTimeImmutable("2017-$from"), new \DateTimeImmutable("2017-$to"), 'jour ferie');
        $avril = new AbsenceCollective(new \DateTimeImmutable('2017-04-01'), new \DateTimeImmutable('2017-04-30'), 'avril');

        $filtered = $absence->excludeFrom($avril);

        $this->assertTrue($filtered instanceof Absence);
        if($expectedFrom === null)
        {
            $this->assertTrue($filtered instanceof NullAbsence);
        }
        else
        {
            $this->assertSame("2017-$expectedFrom", $filtered->from()->format('Y-m-d'));
            $this->assertSame("2017-$expectedTo", $filtered->to()->format('Y-m-d'));
        }
    }

    public function providerTestExcludeFrom()
    {
        return [
            '1 jour inclus' => ['04-10', '04-10', null],
            '1 jour exclus' => ['05-05', '05-05', '05-05', '05-05'],

            '2 jours inclus' => ['04-10', '04-11', null],
            '2 jours exclus' => ['05-10', '05-11', '05-10', '05-11'],
            '2 jours inclus pile au début' => ['04-01', '04-02', null],
            '2 jours inclus pile à la fin' => ['04-29', '04-30', null],
            '2 jours exclus pile avant' => ['03-30', '03-31', '03-30', '03-31'],
            '2 jours exclus pile après' => ['05-01', '05-02', '05-01', '05-02'],

            '2 jours à cheval avant' => ['03-31', '04-01', '03-31', '03-31'],
            '2 jours à cheval après' => ['04-30', '05-01', '05-01', '05-01'],

            '15 jours inclus' => ['04-10', '04-25', null],
            '15 jours exclus' => ['05-10', '05-25', '05-10', '05-25'],
            '15 jours à cheval avant' => ['03-25', '04-08', '03-25', '03-31'],
            '15 jours à cheval après' => ['04-28', '05-12', '05-01', '05-12'],
        ];
    }
}
