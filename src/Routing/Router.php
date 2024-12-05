<?php

namespace Nodest\Framework\Routing;

use Nodest\Framework\Controller\AbstractController;
use Nodest\Framework\Exceptions\MethodNotAllowedException;
use Nodest\Framework\Exceptions\RouteNotFoundException;
use Nodest\Framework\Http\Request\Request;
use Psr\Container\ContainerInterface;

class Router implements RouterInterface
{
    /**
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     */
    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $handler = $request->getRouteHandler();
        $vars = $request->getRouteArgs();

        [$controllerId, $method] = $handler;

        $controller = $container->get($controllerId);

        if (is_subclass_of($controller, AbstractController::class)) {
            $controller->setRequest($request);
        }

        return [[$controller, $method], $vars];
    }
}
