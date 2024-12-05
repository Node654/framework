<?php

namespace Nodest\Framework\Dbal\Event;

use Doctrine\DBAL\Connection;
use Nodest\Framework\Dbal\Event\EntityPersist\EntityPersist;
use Psr\EventDispatcher\EventDispatcherInterface;

class EntityService
{
    public function __construct(
        private Connection $connection,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function store(Entity $entity): int
    {
        $userId = $this->connection->lastInsertId();

        $entity->setId($userId);

        $this->eventDispatcher->dispatch(new EntityPersist($entity));

        return $userId;
    }
}
