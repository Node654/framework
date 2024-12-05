<?php

namespace Nodest\Framework\Http\Middleware\ExtractRouteMiddleware;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Nodest\Framework\Exceptions\MethodNotAllowedException;
use Nodest\Framework\Exceptions\RouteNotFoundException;
use Nodest\Framework\Http\Middleware\MiddlewareInterface;
use Nodest\Framework\Http\Middleware\RequestHandlerMiddleware\RequestHandlerInterface;
use Nodest\Framework\Http\Request\Request;
use Nodest\Framework\Http\Response\Response;

use function FastRoute\simpleDispatcher;

class ExtractRoute implements MiddlewareInterface
{
    public function __construct(
        private array $routes,
    ) {}

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {

            foreach ($this->routes as $route) {
                $r->addRoute(...$route);
            }

        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                $request->setRouteHandler($routeInfo[1][0]);
                $request->setRouteArgs($routeInfo[2]);
                $handler->injectMiddleware($routeInfo[1][1]);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $e = new MethodNotAllowedException("Support HTTP methods: $allowedMethods");
                $e->setStatusCode(405);
                throw $e;
            default:
                $e = new RouteNotFoundException('Route not found');
                $e->setStatusCode(404);
                throw $e;
        }

        return $handler->handle($request);
    }
}
