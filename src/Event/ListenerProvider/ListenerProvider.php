<?php

namespace Nodest\Framework\Event\ListenerProvider;

use App\Listeners\Test;
use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface
{
    private array $listeners = [];

    public function getListenersForEvent(object $event): iterable
    {
        $eventClass = get_class($event);

        if (array_key_exists($eventClass, $this->listeners)) {
            return $this->listeners[$eventClass];
        }

        return [];
    }

    public function addListener(string $event, array $listeners): void
    {
        foreach (array_unique($listeners) as $listener)
        {
            $this->listeners[$event][] = new $listener;
        }
    }
}
