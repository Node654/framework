<?php

namespace Nodest\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Nodest\Framework\Exceptions\MethodNotAllowedException;
use Nodest\Framework\Exceptions\RouteNotFoundException;
use Nodest\Framework\Http\Request;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    public function dispatch(Request $request)
    {
        [$handler, $vars] = $this->extractRouteInfo($request);
        
        [$controller, $method] = $handler;
        
        return [[new $controller, $method], $vars];
    }

    private function extractRouteInfo(Request $request)
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            $routes = require_once BASE_URL.'/routes/web.php';

            foreach ($routes as $route) {
                $r->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()
        );

        switch($routeInfo[0])
        {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                throw new MethodNotAllowedException("Support HTTP methods: $allowedMethods");
            default:
                throw new RouteNotFoundException("Route not found");
        }
    }
}
