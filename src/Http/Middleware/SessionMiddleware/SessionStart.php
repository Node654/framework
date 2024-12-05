<?php

namespace Nodest\Framework\Http\Middleware\SessionMiddleware;

use Nodest\Framework\Http\Middleware\MiddlewareInterface;
use Nodest\Framework\Http\Middleware\RequestHandlerMiddleware\RequestHandlerInterface;
use Nodest\Framework\Http\Request\Request;
use Nodest\Framework\Http\Response\Response;
use Nodest\Framework\Session\SessionInterface;

class SessionStart implements MiddlewareInterface
{
    public function __construct(
        private readonly SessionInterface $session
    ) {}

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();
        $request->setSession($this->session);

        return $handler->handle($request);
    }
}
