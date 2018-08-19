<?php namespace App\Infrastructure\Common\Service;

use App\Domain\Common\Service\EventDispatcherInterface;
use StraTDeS\SharedKernel\Domain\DomainEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SymfonyEventDispatcher implements EventDispatcherInterface
{
    /** @var EventDispatcher */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatch(DomainEvent $domainEvent): void
    {
        $listeners = $this->eventDispatcher->getListeners(get_class($domainEvent));

        foreach($listeners as $listener) {
            call_user_func($listener, $domainEvent);
        }
    }

    public function getListenersGroupedByEventCode(iterable $listeners): array
    {
        $eventCodes = [];
        $listenersArray = iterator_to_array($listeners);

        $events = $this->eventDispatcher->getListeners();

        /** @var DomainEvent $eventClassName */
        foreach ($events as $eventClassName => $eventListeners) {
            foreach($eventListeners as $eventListenerArray) {
                $eventListener = $eventListenerArray[0];
                if (in_array($eventListener, $listenersArray)) {
                    $eventCode = $eventClassName->getCode();
                    if (!isset($eventCodes[$eventCode])) {
                        $eventCodes[$eventCode] = [];
                    }
                    if (!in_array($eventCode, $eventCodes[$eventCode])) {
                        $eventCodes[$eventCode][] = $eventListener;
                    }
                }
            }
        }

        return $eventCodes;
    }
}