<?php

namespace Nodest\Framework\Http\Middleware\RequestHandlerMiddleware;

use Nodest\Framework\Http\Middleware\ExtractRouteMiddleware\ExtractRoute;
use Nodest\Framework\Http\Middleware\RouterDispatchMiddleware\RouterDispatch;
use Nodest\Framework\Http\Middleware\SessionMiddleware\SessionStart;
use Nodest\Framework\Http\Request\Request;
use Nodest\Framework\Http\Response\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [
        ExtractRoute::class,
        SessionStart::class,
        RouterDispatch::class,
    ];

    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function handle(Request $request): Response
    {
        if (empty($this->middlewares)) {
            return new Response('Server handle error!', 500);
        }

        $middlewareClass = array_shift($this->middlewares);

        $middleware = $this->container->get($middlewareClass);

        return $middleware->process($request, $this);
    }

    public function injectMiddleware(array $middlewares = [])
    {
        array_splice($this->middlewares, 0, 0, $middlewares);
    }
}
