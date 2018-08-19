<?php namespace App\Infrastructure\Common\Repository;

use App\Domain\Common\Repository\EventStoreInterface;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use StraTDeS\SharedKernel\Domain\DomainEvent;
use StraTDeS\SharedKernel\Domain\EventStream;
use StraTDeS\SharedKernel\Domain\Id;
use StraTDeS\SharedKernel\Domain\UUIDV4;

class DoctrineEventStore implements EventStoreInterface
{
    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param DomainEvent $domainEvent
     * @throws DBALException
     */
    public function save(DomainEvent $domainEvent): void
    {
        $this->entityManager->getConnection()->insert(
            'event_store',
            $this->getData($domainEvent)
        );
    }

    public function getByEntityId(Id $id): EventStream
    {
        $events = $this->entityManager->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('event_store')
            ->where('entity_id = ?')
            ->setParameter(0, hex2bin(str_replace('-', '', $id->getHumanReadableId())))
            ->orderBy('created_at', 'ASC')
            ->execute()
            ->fetchAll();

        return $this->getEventStreamFromArray($events);
    }

    public function getByEventCodes(array $eventCodes): EventStream
    {
        $eventCodesString = array_map(function (string $eventCode) {
            return "'$eventCode'";
        }, $eventCodes);

        $events = $this->entityManager->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('event_store')
            ->where(
                $this->entityManager->getConnection()->createQueryBuilder()->expr()->in('code', $eventCodesString)
            )
            ->orderBy('created_at', 'ASC')
            ->execute()
            ->fetchAll();

        return $this->getEventStreamFromArray($events);
    }

    public function getAllEvents(): EventStream
    {
        $events = $this->entityManager->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('event_store')
            ->orderBy('created_at', 'ASC')
            ->execute()
            ->fetchAll();

        return $this->getEventStreamFromArray($events);
    }

    public function entityIdExists(Id $id): bool
    {
        $events = $this->entityManager->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('event_store')
            ->where('entity_id = ?')
            ->setParameter(0, hex2bin(str_replace('-', '', $id->getHumanReadableId())))
            ->execute()
            ->rowCount();

        return $events > 0;
    }

    /**
     * @param DomainEvent $domainEvent
     * @return array
     */
    private function getData(DomainEvent $domainEvent): array
    {
        $data = [
            'id' => hex2bin(str_replace('-', '', $domainEvent->getId()->getHumanReadableId())),
            'code' => $domainEvent->getCode(),
            'version' => $domainEvent->getVersion(),
            'entity_id' => hex2bin(str_replace('-', '', $domainEvent->getEntityId()->getHumanReadableId())),
            'data' => json_encode($domainEvent->getData()),
            'event_class' => get_class($domainEvent),
            'creator' => ($domainEvent->getCreator() !== null) ?
                hex2bin(str_replace('-', '', $domainEvent->getCreator()->getHumanReadableId())) : null,
            'created_at' => $domainEvent->getCreatedAt()->format('Y-m-d H:i:s')

        ];

        return $data;
    }

    private function getEventStreamFromArray(array $events): EventStream
    {
        $eventStream = new EventStream();

        foreach ($events as $event) {
            $eventStream->addEvent(
                $this->buildDomainEventFromData($event)
            );
        }

        return $eventStream;
    }

    private function buildDomainEventFromData(array $eventData): DomainEvent
    {
        return DomainEvent::newFromData(
            $eventData['event_class'],
            $this->getIdFromBin($eventData['id']),
            $this->getIdFromBin($eventData['entity_id']),
            ($eventData['creator'] !== null) ? $this->getIdFromBin($eventData['creator']) : null,
            \DateTime::createFromFormat('Y-m-d H:i:s', $eventData['created_at']),
            json_decode($eventData['data']),
            $eventData['version']
        );
    }

    private function getIdFromBin(string $binId): Id
    {
        return UUIDV4::fromString(bin2hex($binId));
    }
}