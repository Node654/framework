<?php

namespace Nodest\Framework\Routing;

class Route
{
    public static function get(string $uri, mixed $handler, array $middlewares = []): array
    {
        return ['GET', $uri, [$handler, $middlewares]];
    }

    public static function post(string $uri, mixed $handler, array $middlewares = []): array
    {
        return ['POST', $uri, [$handler, $middlewares]];
    }
}
