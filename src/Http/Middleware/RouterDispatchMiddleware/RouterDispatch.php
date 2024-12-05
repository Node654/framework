<?php

namespace Nodest\Framework\Http\Middleware\RouterDispatchMiddleware;

use Nodest\Framework\Http\Middleware\MiddlewareInterface;
use Nodest\Framework\Http\Middleware\RequestHandlerMiddleware\RequestHandlerInterface;
use Nodest\Framework\Http\Request\Request;
use Nodest\Framework\Http\Response\Response;
use Nodest\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    ) {}

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        [$handler, $vars] = $this->router->dispatch($request, $this->container);
        $response = call_user_func_array($handler, $vars);

        return $response;
    }
}
