<?php namespace App\Infrastructure\Common\Repository;

use App\Domain\Common\Repository\EventStoreInterface;
use App\Domain\Common\Service\EventManagerService;
use StraTDeS\SharedKernel\Domain\Entity;
use StraTDeS\SharedKernel\Domain\EntityNotFoundException;
use StraTDeS\SharedKernel\Domain\EventSourcedEntity;
use StraTDeS\SharedKernel\Domain\EventSourcedRepository;
use StraTDeS\SharedKernel\Domain\Id;

abstract class DoctrineEventSourcedRepository implements EventSourcedRepository
{
    /** @var EventManagerService */
    private $eventManager;

    /** @var EventStoreInterface */
    private $eventStore;

    public function __construct(EventManagerService $eventManager, EventStoreInterface $eventStore)
    {
        $this->eventManager = $eventManager;
        $this->eventStore = $eventStore;
    }

    abstract public function getEntityName(): string;

    /**
     * @param Id $id
     * @return Entity
     * @throws EntityNotFoundException
     * @throws \ReflectionException
     */
    public function get(Id $id): Entity
    {
        $eventStream = $this->eventStore->getByEntityId($id);

        if (count($eventStream->getEvents()) === 0) {
            throw new EntityNotFoundException('Entity with ID '.$id->getHumanReadableId().'not found');
        }

        $reflectedEntity = new \ReflectionClass($this->getEntityName());
        /** @var EventSourcedEntity $entity */
        $entity = $reflectedEntity->newInstanceWithoutConstructor();

        return $entity::reconstituteFromEventStream($this->getEntityName(), $eventStream);
    }

    /**
     * @param Id $id
     * @return null|Entity
     * @throws \ReflectionException
     */
    public function find(Id $id): ?Entity
    {
        try {
            return $this->get($id);
        } catch(EntityNotFoundException $e) {
            return null;
        }
    }

    public function save(Entity $entity): void
    {
        $this->eventManager->handle($entity->pullEventStream());
    }
}