<?php

namespace Nodest\Framework\Http;

use Nodest\Framework\Routing\RouterInterface;

class Kernel
{
    public function __construct(
        private RouterInterface $router
    ) {}

    public function handle(Request $request): Response
    {
        try {
            [$handler, $vars] = $this->router->dispatch($request);
            $response = call_user_func_array($handler, $vars);
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage());
        }
        return $response;
    }
}
