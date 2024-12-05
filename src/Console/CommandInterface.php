<?php

namespace Nodest\Framework\Console;

interface CommandInterface
{
    public function execute(array $arguments = []): int;
}
