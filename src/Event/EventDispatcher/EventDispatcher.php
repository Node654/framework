<?php

namespace Nodest\Framework\Event\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private ListenerProviderInterface $listenerProvider
    ) {}

    public function dispatch(object $event)
    {
        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {

            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return;
            }

            $listener($event);
        }
    }

    public function getListenerProvider(): ListenerProviderInterface
    {
        return $this->listenerProvider;
    }
}
