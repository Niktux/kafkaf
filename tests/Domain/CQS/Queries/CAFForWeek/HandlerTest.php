<?php

declare(strict_types = 1);

namespace Niktux\Kafkaf\Domain\CQS\Queries\CAFForWeek;

use PHPUnit\Framework\TestCase;
use Niktux\Kafkaf\Domain\Absences\AbsenceProvider;
use Niktux\Kafkaf\Domain\CollaborateurCollection;
use Niktux\Kafkaf\Domain\Collaborateur;
use Niktux\Kafkaf\Persistence\DataTransferObjects as DTO;
use Niktux\Kafkaf\Domain\Absences\AbsenceProviderCollection;
use Niktux\Kafkaf\Domain\Absences\AbsenceCollection;
use Niktux\Kafkaf\Domain\Absences\Conge;
use Niktux\Kafkaf\Persistence\ReadableCollaborateurRepository;

class HandlerTest extends TestCase
{
    private const
        TOTO_UUID = 'bafa-caca',
        CLOUD_UUID = 'effe-l33t',
        CONGE_UUID = 'faf-faf-faf';

    private
        $handler;

    protected function setUp()
    {
        $toto = new Collaborateur(
            DTO\Collaborateur::construct(self::TOTO_UUID, 'Toto', 'Poney', 't@k.fr')
        );
        $cloud = new Collaborateur(
            DTO\Collaborateur::construct(self::CLOUD_UUID, 'Cloud', 'Français', 'c@k.fr')
        );

        $conge = new DTO\Conge();
        $conge->uuid = self::CONGE_UUID;
        $conge->from = new \DateTimeImmutable('2017-05-15');
        $conge->to = new \DateTimeImmutable('2017-06-04');
        $conge->collaborateurUuid = self::TOTO_UUID;

        $provider = new class($conge) implements AbsenceProvider
        {
            public function __construct($conge) { $this->conge = $conge; }

            public function list(int $weekFrom, int $weekTo): AbsenceCollection
            {
                return new AbsenceCollection([
                    new Conge($this->conge)
                ]);
            }
        };

        $providers = new AbsenceProviderCollection([$provider]);

        $repository = new class($toto, $cloud) implements ReadableCollaborateurRepository
        {
            public function __construct($toto, $cloud) { $this->toto = $toto; $this->cloud = $cloud; }

            public function find(string $uuid): ?Collaborateur { return null; }

            public function findAll(): CollaborateurCollection
            {
                $collection = new CollaborateurCollection();

                $collection->addCollaborateur($this->toto);
                $collection->addCollaborateur($this->cloud);

                return $collection;
            }
        };

        $this->handler = new Handler($providers, $repository);
    }

    public function testHandle()
    {
        $query = new CAFForWeekQuery(21);
        $result = $this->handler->handle($query);
        $absences = $result->getAbsences();

        $totoAbsences = iterator_to_array($absences[self::TOTO_UUID]['absences']);
        $cloudAbsences = iterator_to_array($absences[self::CLOUD_UUID]['absences']);

        $this->assertCount(1, $totoAbsences);
        $absence = reset($totoAbsences);
        $this->assertSame(4, $absence->duration()); // 4 not 5 because of ascension

        $this->assertCount(0, $cloudAbsences);
    }
}
