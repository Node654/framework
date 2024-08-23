<?php

namespace Nodest\Framework\Tests;

class Two
{
    public function __construct(
        private readonly Tree $tree,
        private readonly Pho $pho,
    ) {}

    public function getTree(): Tree
    {
        return $this->tree;
    }

    public function getPho(): Pho
    {
        return $this->pho;
    }
}
