<?php

namespace Nodest\Framework\Tests;

class One
{
    public function __construct(
        private readonly Two $two,
    ) {}

    public function getTwo(): Two
    {
        return $this->two;
    }
}
