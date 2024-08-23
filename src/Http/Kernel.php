<?php

namespace Nodest\Framework\Http;

use Nodest\Framework\Exceptions\HttpException;
use Nodest\Framework\Routing\RouterInterface;

class Kernel
{
    public function __construct(
        private readonly RouterInterface $router
    ) {}

    public function handle(Request $request): Response
    {
        try {
            [$handler, $vars] = $this->router->dispatch($request);
            $response = call_user_func_array($handler, $vars);
        } catch (HttpException $e) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), $e->getCode());
        }

        return $response;
    }
}
