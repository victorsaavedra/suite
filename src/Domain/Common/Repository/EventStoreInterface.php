<?php namespace App\Domain\Common\Repository;

use StraTDeS\SharedKernel\Domain\DomainEvent;
use StraTDeS\SharedKernel\Domain\EventStream;
use StraTDeS\SharedKernel\Domain\Id;
use StraTDeS\SharedKernel\Infrastructure\RepositoryException;

interface EventStoreInterface
{
    /**
     * @param DomainEvent $domainEvent
     * @throws RepositoryException
     */
    public function save(DomainEvent $domainEvent): void;

    public function getByEntityId(Id $id): EventStream;

    public function getByEventCodes(array $eventCodes): EventStream;

    public function getAllEvents(): EventStream;

    public function entityIdExists(Id $id): bool;
}