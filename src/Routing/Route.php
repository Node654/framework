<?php

namespace Nodest\Framework\Routing;

class Route
{
    public static function get(string $uri, mixed $handler): array
    {
        return ['GET', $uri, $handler];
    }

    public static function post(string $uri, mixed $handler): array
    {
        return ['POST', $uri, $handler];
    }
}
