<?php

namespace Nodest\Framework\Dbal\Event;

abstract class Entity
{
    abstract public function setId(int $id): void;
}
