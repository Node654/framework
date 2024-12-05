<?php

namespace Nodest\Framework\Dbal\Event\EntityPersist;

use Nodest\Framework\Dbal\Event\Entity;
use Nodest\Framework\Event\Event;

class EntityPersist extends Event
{
    public function __construct(private Entity $entity) {}

    public function getEntity(): Entity
    {
        return $this->entity;
    }
}
