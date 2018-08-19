<?php namespace App\Domain\Common\Service;

use StraTDeS\SharedKernel\Domain\DomainEvent;

interface EventDispatcherInterface
{
    public function dispatch(DomainEvent $domainEvent): void;

    public function getListenersGroupedByEventCode(iterable $listeners): array;
}